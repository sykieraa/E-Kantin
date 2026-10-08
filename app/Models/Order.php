<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_READY = 'ready';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    public const PAYMENT_BALANCE = 'balance';

    public const PAYMENT_OUTLET = 'outlet';

    protected $fillable = [
        'student_id', 'canteen_id', 'order_number', 'queue_number', 'queue_date',
        'queue_code', 'total', 'status', 'payment_status', 'payment_method', 'cancel_reason', 'ordered_at',
    ];

    protected function casts(): array
    {
        return [
            'queue_date' => 'date',
            'ordered_at' => 'datetime',
            'total' => 'integer',
            'queue_number' => 'integer',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function canteen(): BelongsTo
    {
        return $this->belongsTo(Canteen::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_READY => 'Siap diambil',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];
    }

    public static function paymentMethodOptions(): array
    {
        return [
            self::PAYMENT_BALANCE => 'Saldo E-Kantin',
            self::PAYMENT_OUTLET => 'Tunai saat pengambilan',
        ];
    }

    public function paymentMethodLabel(): string
    {
        return self::paymentMethodOptions()[$this->payment_method] ?? 'Tunai saat pengambilan';
    }

    public function statusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? $this->status;
    }

    /**
     * Status berikutnya yang boleh dipilih penjual dari status sekarang.
     * Mencegah lompatan status yang tidak masuk akal (misal selesai -> menunggu).
     */
    public function allowedNextStatuses(): array
    {
        return match ($this->status) {
            self::STATUS_PENDING => [self::STATUS_PROCESSING, self::STATUS_CANCELLED],
            self::STATUS_PROCESSING => [self::STATUS_READY, self::STATUS_CANCELLED],
            self::STATUS_READY => [self::STATUS_COMPLETED],
            default => [],
        };
    }

    public function isActive(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING, self::STATUS_READY], true);
    }
}
