<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // PERINGATAN: password demo di bawah ini WAJIB diganti
        // sebelum aplikasi dipakai sungguhan di sekolah.
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator Sekolah',
                'email' => 'admin@ekantin.test',
                'password' => env('SEED_ADMIN_PASSWORD'),
                'role' => User::ROLE_ADMIN,
            ]
        );
    }
}
