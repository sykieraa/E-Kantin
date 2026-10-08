<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_SELLER = 'seller';

    public const ROLE_STUDENT = 'student';

    protected $fillable = [
    'username',
    'name',
    'email',
    'password',
    'role',
    'is_active',
    'must_change_password',
    'activation_token_hash',
    'activation_expires_at',
];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            // Laravel akan otomatis melakukan hashing saat password di-set.
            'password' => 'hashed',
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
            'activation_expires_at' => 'datetime',
        ];
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function canteen(): HasOne
    {
        return $this->hasOne(Canteen::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isSeller(): bool
    {
        return $this->role === self::ROLE_SELLER;
    }

    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    /**
     * Halaman tujuan setelah login, sesuai role.
     */
    public function homeRoute(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => route('admin.dashboard'),
            self::ROLE_SELLER => route('seller.dashboard'),
            default => route('student.dashboard'),
        };
    }
}
