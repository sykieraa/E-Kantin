<?php

namespace Database\Seeders;

use App\Models\Canteen;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'name');

        $menus = [
            'A' => [
                ['Nasi Goreng Spesial', 'Makanan Berat', 'Nasi goreng dengan telur mata sapi dan acar.', 15000, 20],
                ['Nasi Ayam Geprek', 'Makanan Berat', 'Ayam geprek sambal bawang, nasi, dan lalapan.', 17000, 15],
                ['Soto Ayam', 'Makanan Berat', 'Soto ayam kuah bening dengan bihun dan koya.', 13000, 12],
                ['Es Teh Manis', 'Minuman', 'Teh manis dingin ukuran besar.', 4000, 50],
                ['Es Jeruk', 'Minuman', 'Perasan jeruk peras segar.', 6000, 30],
                ['Paket Nasi + Es Teh', 'Paket Hemat', 'Nasi goreng spesial plus es teh manis.', 18000, 10],
            ],
            'B' => [
                ['Mie Ayam Bakso', 'Makanan Berat', 'Mie ayam dengan tiga butir bakso sapi.', 14000, 18],
                ['Bakso Kuah', 'Makanan Berat', 'Bakso sapi kuah kaldu dengan mie dan sawi.', 12000, 20],
                ['Batagor', 'Makanan Ringan', 'Batagor siram bumbu kacang.', 10000, 16],
                ['Risoles Mayo', 'Gorengan', 'Risoles isi smoked beef dan mayones.', 5000, 25],
                ['Tahu Isi', 'Gorengan', 'Tahu goreng isi sayuran, disajikan hangat.', 2000, 40],
                ['Es Campur', 'Minuman', 'Es campur dengan cincau, kelapa muda, dan sirup.', 8000, 14],
            ],
        ];

        foreach ($menus as $code => $items) {
            $canteen = Canteen::where('code', $code)->first();

            if (! $canteen) {
                continue;
            }

            foreach ($items as [$name, $category, $description, $price, $stock]) {
                Product::updateOrCreate(
                    ['canteen_id' => $canteen->id, 'name' => $name],
                    [
                        'category_id' => $categories[$category] ?? null,
                        'description' => $description,
                        'price' => $price,
                        'stock' => $stock,
                        'is_available' => true,
                    ]
                );
            }
        }
    }
}
