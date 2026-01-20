<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddSampleProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tambahkan beberapa produk sample
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
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['code' => $product['code']],
                $product
            );
        }
        
        echo "Sample products added successfully!\n";
    }
}