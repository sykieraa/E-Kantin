<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ActivationController extends Controller
{
    /**
     * Halaman input NIS + kode aktivasi.
     */
    public function create(): View
    {
        return view('student.activation');
    }

    /**
     * Verifikasi NIS + kode aktivasi.
     */
    public function verify(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nis' => [
                'required',
                'string',
                'max:30',
            ],
            'code' => [
                'required',
                'string',
                'size:8',
            ],
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'code.required' => 'Kode aktivasi wajib diisi.',
            'code.size' => 'Kode aktivasi harus 8 karakter.',
        ]);

        $user = User::query()
            ->where('role', User::ROLE_STUDENT)
            ->where('username', $data['nis'])
            ->where('activation_token_hash', hash('sha256', strtoupper($data['code'])))
            ->whereNotNull('activation_expires_at')
            ->where('activation_expires_at', '>', now())
            ->first();

        if (! $user) {
            return back()
                ->withInput()
                ->withErrors([
                    'code' => 'NIS atau kode aktivasi salah, atau kode sudah kedaluwarsa.',
                ]);
        }

        session([
            'activation_user_id' => $user->id,
        ]);

        return redirect()->route('student.activation.password');
    }

    /**
     * Halaman membuat password.
     */
    public function password(): View|RedirectResponse
    {
        $user = $this->getActivationUser();

        if (! $user) {
            return redirect()
                ->route('student.activation')
                ->withErrors([
                    'activation' => 'Sesi aktivasi tidak valid atau sudah berakhir.',
                ]);
        }

        return view('student.setup-password', [
            'student' => $user->student,
        ]);
    }

    /**
     * Menyimpan password baru.
     */
    public function storePassword(Request $request): RedirectResponse
    {
        $user = $this->getActivationUser();

        if (! $user) {
            return redirect()
                ->route('student.activation')
                ->withErrors([
                    'activation' => 'Sesi aktivasi tidak valid atau sudah berakhir.',
                ]);
        }

        $data = $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user->update([
            // User model sudah menggunakan cast 'hashed'.
            'password' => $data['password'],

            'must_change_password' => false,

            // Kode aktivasi langsung dibuat tidak berlaku.
            'activation_token_hash' => null,
            'activation_expires_at' => null,
        ]);

        session()->forget('activation_user_id');

        return redirect()
            ->route('login')
            ->with(
                'status',
                'Password berhasil dibuat. Silakan login menggunakan NIS dan password baru.'
            );
    }

    /**
     * Mengambil user yang sedang melakukan aktivasi.
     */
    private function getActivationUser(): ?User
    {
        $userId = session('activation_user_id');

        if (! $userId) {
            return null;
        }

        return User::query()
            ->where('id', $userId)
            ->where('role', User::ROLE_STUDENT)
            ->whereNotNull('activation_token_hash')
            ->whereNotNull('activation_expires_at')
            ->where('activation_expires_at', '>', now())
            ->first();
    }
}