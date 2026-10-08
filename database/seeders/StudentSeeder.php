<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['nis' => '2024001', 'name' => 'Ahmad Fauzi', 'class_name' => 'XI RPL 1'],
            ['nis' => '2024002', 'name' => 'Siti Nurhaliza', 'class_name' => 'XI RPL 1'],
            ['nis' => '2024003', 'name' => 'Rizky Pratama', 'class_name' => 'XI RPL 2'],
            ['nis' => '2024004', 'name' => 'Dewi Lestari', 'class_name' => 'XII TKJ 1'],
            ['nis' => '2024005', 'name' => 'Bagas Saputra', 'class_name' => 'X RPL 1'],
        ];

        foreach ($students as $row) {
            // Username siswa = NIS; password awal = nama depan_NIS.
            $user = User::updateOrCreate(
                ['username' => $row['nis']],
                [
                    'name' => $row['name'],
                    'password' => Str::random(64),
                    'role' => User::ROLE_STUDENT,
                ]
            );

            $user->student()->updateOrCreate(['user_id' => $user->id], $row);
        }
    }
}
