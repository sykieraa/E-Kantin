<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SellerController extends Controller
{
    public function index(): View
    {
        $sellers = User::with('canteen')
            ->where('role', User::ROLE_SELLER)
            ->orderBy('name')
            ->paginate(15);

        return view('admin.sellers.index', compact('sellers'));
    }

    public function create(): View
    {
        return view('admin.sellers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        DB::transaction(function () use ($data) {
            $seller = User::create([
                'username' => $data['username'],
                'name' => $data['name'],
                'email' => $data['email'] ?: null,
                'password' => $data['password'],
                'role' => User::ROLE_SELLER,
            ]);

            $seller->canteen()->create($this->canteenData($data));
        });

        return redirect()->route('admin.sellers.index')
            ->with('success', 'Penjual dan kantin berhasil ditambahkan.');
    }

    public function edit(User $seller): View
    {
        abort_unless($seller->isSeller(), 404);

        return view('admin.sellers.edit', compact('seller'));
    }

    public function update(Request $request, User $seller): RedirectResponse
    {
        abort_unless($seller->isSeller(), 404);

        $data = $this->validateData($request, $seller);

        $payload = [
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'] ?: null,
            'is_active' => $request->boolean('is_active'),
        ];

        if (! empty($data['password'])) {
            $payload['password'] = $data['password'];
        }

        DB::transaction(function () use ($seller, $payload, $data) {
            $seller->update($payload);
            $seller->canteen()->updateOrCreate([], $this->canteenData($data));
        });

        return redirect()->route('admin.sellers.index')->with('success', 'Data penjual dan kantin diperbarui.');
    }

    public function destroy(User $seller): RedirectResponse
    {
        abort_unless($seller->isSeller(), 404);

        if ($seller->canteen) {
            if ($seller->canteen->orders()->exists()) {
                return back()->with('error', 'Penjual ini memiliki kantin dengan riwayat pesanan sehingga tidak bisa dihapus. Nonaktifkan akun bila sudah tidak beroperasi.');
            }

            $seller->canteen->delete();
        }

        $seller->delete();

        return redirect()->route('admin.sellers.index')->with('success', 'Penjual berhasil dihapus.');
    }

    private function validateData(Request $request, ?User $seller = null): array
    {
        return $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($seller?->id)],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100', Rule::unique('users', 'email')->ignore($seller?->id)],
            'password' => [$seller ? 'nullable' : 'required', 'string', 'min:8'],
            'canteen_name' => ['required', 'string', 'max:100'],
            'canteen_code' => ['required', 'string', 'max:2', 'regex:/^[A-Za-z]{1,2}$/', Rule::unique('canteens', 'code')->ignore($seller?->canteen?->id)],
            'canteen_description' => ['nullable', 'string', 'max:500'],
            'canteen_status' => ['required', Rule::in(array_keys(Canteen::statusOptions()))],
        ], [
            'username.unique' => 'Username ini sudah dipakai.',
            'canteen_code.regex' => 'Kode kantin hanya boleh 1-2 huruf, contoh: A atau AB.',
            'canteen_code.unique' => 'Kode kantin sudah dipakai.',
        ]);
    }

    private function canteenData(array $data): array
    {
        return [
            'name' => $data['canteen_name'],
            'code' => strtoupper($data['canteen_code']),
            'description' => $data['canteen_description'] ?: null,
            'status' => $data['canteen_status'],
        ];
    }
}
