<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CheckProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        
        echo "Total products: " . $products->count() . "\n";
        
        foreach ($products as $product) {
            echo "Code: {$product->code}, Name: {$product->name}, Stock: {$product->stock}\n";
        }
        
        // Cek produk dengan kode BRG001
        $brg001 = Product::where('code', 'BRG001')->first();
        if ($brg001) {
            echo "\nProduk BRG001 ditemukan!\n";
            echo "Stock: {$brg001->stock} (" . ($brg001->stock > 0 ? 'Tersedia' : 'Habis') . ")\n";
        } else {
            echo "\nProduk BRG001 tidak ditemukan!\n";
        }
    }
}