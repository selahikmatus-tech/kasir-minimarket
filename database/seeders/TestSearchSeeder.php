<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSearchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $search = 'BRG001';
        
        // Test query lama (salah)
        echo "Query lama (salah):\n";
        $products1 = Product::where('code', 'like', "%{$search}%")
            ->orWhere('name', 'like', "%{$search}%")
            ->where('stock', '>', 0)
            ->get();
        
        echo "Hasil query lama: " . $products1->count() . " produk\n";
        
        // Test query baru (benar)
        echo "\nQuery baru (benar):\n";
        $products2 = Product::where(function($query) use ($search) {
                $query->where('code', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%");
            })
            ->where('stock', '>', 0)
            ->get();
            
        echo "Hasil query baru: " . $products2->count() . " produk\n";
        
        foreach ($products2 as $product) {
            echo "- {$product->code}: {$product->name} (Stok: {$product->stock})\n";
        }
        
        // Test pencarian nama
        echo "\nTest pencarian 'Indomie':\n";
        $search2 = 'Indomie';
        $products3 = Product::where(function($query) use ($search2) {
                $query->where('code', 'like', "%{$search2}%")
                      ->orWhere('name', 'like', "%{$search2}%");
            })
            ->where('stock', '>', 0)
            ->get();
            
        echo "Hasil pencarian 'Indomie': " . $products3->count() . " produk\n";
    }
}