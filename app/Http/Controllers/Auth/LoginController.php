<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Login memakai kolom `username`.
     * Siswa mengisi NIS (NIS disimpan juga sebagai username),
     * admin & penjual mengisi username masing-masing.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'NIS atau username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'username' => 'Data login tidak ditemukan. Periksa kembali NIS/username dan password.',
            ]);
        }

        if (! $request->user()->is_active) {
            Auth::logout();

            throw ValidationException::withMessages([
                'username' => 'Akun ini sedang dinonaktifkan. Hubungi admin sekolah.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended($request->user()->homeRoute());
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Kamu sudah keluar.');
    }
}
