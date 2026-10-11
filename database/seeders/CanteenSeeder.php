<?php

namespace Database\Seeders;

use App\Models\Canteen;
use App\Models\User;
use Illuminate\Database\Seeder;

class CanteenSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'username' => 'penjual1',
                'name' => 'Ibu Sri Wahyuni',
                'canteen' => 'Kantin Bu Sri',
                'code' => 'A',
                'description' => 'Menyediakan nasi, lauk, dan aneka minuman dingin.',
                'status' => Canteen::STATUS_OPEN,
            ],
            [
                'username' => 'penjual2',
                'name' => 'Pak Budi Santoso',
                'canteen' => 'Kantin Pak Budi',
                'code' => 'B',
                'description' => 'Spesialis mie, bakso, dan gorengan hangat.',
                'status' => Canteen::STATUS_OPEN,
            ],
        ];

        foreach ($data as $row) {
            $user = User::updateOrCreate(
                ['username' => $row['username']],
                [
                    'name' => $row['name'],
                    'email' => $row['username'].'@ekantin.test',
                    'password' => env('SEED_SELLER_PASSWORD'),
                    'role' => User::ROLE_SELLER,
                ]
            );

            Canteen::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $row['canteen'],
                    'code' => $row['code'],
                    'description' => $row['description'],
                    'status' => $row['status'],
                ]
            );
        }
    }
}
