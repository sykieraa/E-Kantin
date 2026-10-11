@csrf
@isset($product)
    @method('PUT')
@endisset

<div>
    <label for="name" class="label">Nama menu</label>
    <input id="name" name="name" value="{{ old('name', $product->name ?? '') }}" class="input" required>
</div>

<div>
    <label for="category_id" class="label">Kategori</label>
    <select id="category_id" name="category_id" class="input">
        <option value="">Tanpa kategori</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? null) == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label for="description" class="label">Deskripsi</label>
    <textarea id="description" name="description" rows="3" class="input">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label for="price" class="label">Harga</label>
        <input id="price" name="price" type="number" min="0" value="{{ old('price', $product->price ?? 0) }}" class="input" required>
        <p class="mt-1 text-xs text-gray-500">Tulis angka saja, contoh: 15000</p>
    </div>
    <div>
        <label for="stock" class="label">Stok</label>
        <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $product->stock ?? 0) }}" class="input" required>
    </div>
</div>

<div>
    <label for="image" class="label">Foto menu</label>

```
@isset($product)
    @if ($product->image)
        <img
            src="{{ $product->image }}"
            alt="{{ $product->name }}"
            class="mb-2 h-24 w-24 rounded-xl object-cover"
        >
    @endif
@endisset

<input
    id="image"
    name="image"
    type="file"
    accept="image/jpeg,image/png,image/webp"
    class="input"
>

<p class="mt-1 text-xs text-gray-500">
    Format jpg, jpeg, png, atau webp. Maksimal 2 MB.
    Kosongkan bila tidak ingin mengganti foto.
</p>

@error('image')
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
```

</div>

<label class="flex items-center gap-2 text-sm text-gray-700">
    <input type="checkbox" name="is_available" value="1" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500"
           @checked(old('is_available', $product->is_available ?? true))>
    Tampilkan menu ini ke siswa
</label>
