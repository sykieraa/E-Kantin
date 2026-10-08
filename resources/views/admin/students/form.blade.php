@csrf
@isset($student)
    @method('PUT')
@endisset

<div>
    <label for="nis" class="label">NIS</label>
    <input id="nis" name="nis" value="{{ old('nis', $student->nis ?? '') }}" class="input" required>
    <p class="mt-1 text-xs text-gray-500">NIS sekaligus dipakai sebagai username untuk login siswa.</p>
</div>

<div>
    <label for="name" class="label">Nama lengkap</label>
    <input id="name" name="name" value="{{ old('name', $student->name ?? '') }}" class="input" required>
</div>

<div>
    <label for="class_name" class="label">Kelas</label>
    <input id="class_name" name="class_name" value="{{ old('class_name', $student->class_name ?? '') }}" class="input" required
           placeholder="contoh: XI RPL 1">
</div>

<div>
    <label for="phone" class="label">Nomor WhatsApp</label>
    <input
        id="phone"
        name="phone"
        type="tel"
        value="{{ old('phone', $student->phone ?? '') }}"
        class="input w-full"
        placeholder="Contoh: 081234567890"
        maxlength="20"
    >
    <p class="mt-1 text-xs text-gray-500">
        Gunakan nomor WhatsApp aktif siswa. Nomor harus diawali 08 atau 62.
        Kosongkan jika belum tersedia.
    </p>

    @error('phone')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<p class="rounded-xl bg-brand-50 px-4 py-3 text-sm text-brand-800">
    Password siswa dibuat oleh siswa sendiri dan tidak dapat dilihat oleh admin.
</p>