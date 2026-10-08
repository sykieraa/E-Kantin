@csrf
@isset($seller)
    @method('PUT')
@endisset

@php($canteen = ($seller ?? null)?->canteen)

<div>
    <label for="username" class="label">Username</label>
    <input id="username" name="username" value="{{ old('username', $seller->username ?? '') }}" class="input" required>
</div>

<div>
    <label for="name" class="label">Nama penjual</label>
    <input id="name" name="name" value="{{ old('name', $seller->name ?? '') }}" class="input" required>
</div>

<div>
    <label for="email" class="label">Email (opsional)</label>
    <input id="email" name="email" type="email" value="{{ old('email', $seller->email ?? '') }}" class="input">
</div>

<div>
    <label for="password" class="label">Password {{ isset($seller) ? '(kosongkan bila tidak diubah)' : '' }}</label>
    <input id="password" name="password" type="password" class="input" @if (! isset($seller)) required @endif>
</div>

@isset($seller)
    <label class="flex items-center gap-2 text-sm text-gray-700">
        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500"
               @checked(old('is_active', $seller->is_active))>
        Akun aktif dan boleh login
    </label>
@endisset

<div class="border-t border-gray-200 pt-5">
    <h2 class="text-base font-bold">Data kantin</h2>
    <p class="mt-1 text-sm text-gray-500">Kantin ini akan dikelola oleh penjual di atas.</p>
</div>

<div>
    <label for="canteen_name" class="label">Nama kantin</label>
    <input id="canteen_name" name="canteen_name" value="{{ old('canteen_name', $canteen?->name) }}" class="input" required>
</div>

<div>
    <label for="canteen_code" class="label">Kode kantin</label>
    <input id="canteen_code" name="canteen_code" value="{{ old('canteen_code', $canteen?->code) }}" class="input uppercase" maxlength="2" required>
    <p class="mt-1 text-xs text-gray-500">1-2 huruf, misalnya A atau AB. Dipakai pada nomor antrean.</p>
</div>

<div>
    <label for="canteen_description" class="label">Deskripsi kantin (opsional)</label>
    <textarea id="canteen_description" name="canteen_description" rows="3" class="input">{{ old('canteen_description', $canteen?->description) }}</textarea>
</div>

<div>
    <label for="canteen_status" class="label">Status kantin</label>
    <select id="canteen_status" name="canteen_status" class="input" required>
        @foreach (\App\Models\Canteen::statusOptions() as $value => $label)
            <option value="{{ $value }}" @selected(old('canteen_status', $canteen?->status ?? 'closed') === $value)>{{ $label }}</option>
        @endforeach
    </select>
</div>
