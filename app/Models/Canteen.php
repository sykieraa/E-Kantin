<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Canteen extends Model
{
    use HasFactory;

    public const STATUS_OPEN = 'open';

    public const STATUS_TEMPORARILY_CLOSED = 'temporarily_closed';

    public const STATUS_CLOSED = 'closed';

    protected $fillable = ['user_id', 'name', 'code', 'description', 'status'];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Hanya kantin berstatus "Buka" yang boleh menerima pesanan baru.
     */
    public function isAcceptingOrders(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_OPEN => 'Buka',
            self::STATUS_TEMPORARILY_CLOSED => 'Tutup sementara',
            default => 'Tutup',
        };
    }

    /**
     * Alasan penolakan pesanan, untuk ditampilkan ke siswa.
     */
    public function closedMessage(): ?string
    {
        return match ($this->status) {
            self::STATUS_OPEN => null,
            self::STATUS_TEMPORARILY_CLOSED => 'Kantin sedang tidak menerima pesanan.',
            default => 'Kantin sedang tutup.',
        };
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_OPEN => 'Buka',
            self::STATUS_TEMPORARILY_CLOSED => 'Tutup sementara',
            self::STATUS_CLOSED => 'Tutup',
        ];
    }
}
