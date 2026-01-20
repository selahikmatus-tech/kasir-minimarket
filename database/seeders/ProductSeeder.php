<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah sudah ada data produk
        if (Product::count() > 0) {
            return; // Jika sudah ada data, tidak usah buat lagi
        }
        
        // Buat data produk baru
        $products = [
            [
                'code' => 'BRG001',
                'name' => 'Indomie Goreng',
                'price' => 3500,
                'stock' => 100,
            ],
            [
                'code' => 'BRG002',
                'name' => 'Sabun Mandi Lifebuoy',
                'price' => 2500,
                'stock' => 50,
            ],
            [
                'code' => 'BRG003',
                'name' => 'Shampoo Clear',
                'price' => 8000,
                'stock' => 30,
            ],
            [
                'code' => 'BRG004',
                'name' => 'Pasta Gigi Pepsodent',
                'price' => 5000,
                'stock' => 40,
            ],
            [
                'code' => 'BRG005',
                'name' => 'Minyak Goreng Bimoli',
                'price' => 15000,
                'stock' => 25,
            ],
            [
                'code' => 'BRG006',
                'name' => 'Gula Pasir',
                'price' => 12000,
                'stock' => 60,
            ],
            [
                'code' => 'BRG007',
                'name' => 'Kopi Kapal Api',
                'price' => 2500,
                'stock' => 80,
            ],
            [
                'code' => 'BRG008',
                'name' => 'Teh Celup Sariwangi',
                'price' => 1500,
                'stock' => 100,
            ],
            [
                'code' => 'BRG009',
                'name' => 'Roti Tawar',
                'price' => 8000,
                'stock' => 20,
            ],
            [
                'code' => 'BRG010',
                'name' => 'Susu UHT Indomilk',
                'price' => 6000,
                'stock' => 35,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}