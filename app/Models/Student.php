<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nis',
        'name',
        'class_name',
        'balance',
        'phone',
    ];

    protected function casts(): array
    {
        return [
            'balance' => 'integer',
        ];
    }

    /**
     * Nomor WhatsApp dalam format internasional untuk wa.me.
     */
    public function getWaNumberAttribute(): ?string
    {
        if (blank($this->phone)) {
            return null;
        }

        $number = preg_replace('/\D+/', '', $this->phone);

        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }

        return $number ?: null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}