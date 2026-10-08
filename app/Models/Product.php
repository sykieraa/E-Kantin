<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'canteen_id', 'category_id', 'name', 'description',
        'price', 'stock', 'image', 'is_available',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'stock' => 'integer',
            'is_available' => 'boolean',
        ];
    }

    public function canteen(): BelongsTo
    {
        return $this->belongsTo(Canteen::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Satu-satunya aturan "bisa dipesan": diaktifkan penjual DAN stok masih ada.
     */
    public function isOrderable(): bool
    {
        return $this->is_available && $this->stock > 0;
    }

    public function scopeOrderable(Builder $query): Builder
    {
        return $query->where('is_available', true)->where('stock', '>', 0);
    }

    public function scopeLowStock(Builder $query, int $threshold = 5): Builder
    {
        return $query->where('stock', '<=', $threshold);
    }
}
