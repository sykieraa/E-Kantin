<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Pengecekan kepemilikan kantin dilakukan di controller.
        return $this->user()?->isSeller() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'integer', 'min:0', 'max:10000000'],
            'stock' => ['required', 'integer', 'min:0', 'max:100000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama menu wajib diisi.',
            'price.required' => 'Harga wajib diisi.',
            'price.integer' => 'Harga harus berupa angka tanpa titik atau koma.',
            'stock.required' => 'Stok wajib diisi.',
            'image.max' => 'Ukuran foto maksimal 2 MB.',
            'image.mimes' => 'Format foto harus jpg, jpeg, png, atau webp.',
        ];
    }
}
