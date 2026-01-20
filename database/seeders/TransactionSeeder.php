<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $user = User::where('email', 'admin@kasir.com')->first();
        
        if (!$user) {
            $user = User::first();
        }
        
        // Data transaksi untuk 30 hari terakhir untuk grafik yang bagus
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays($i);
            
            // Buat 3-8 transaksi per hari dengan variasi
            $transactionsPerDay = rand(3, 8);
            
            for ($j = 0; $j < $transactionsPerDay; $j++) {
                // Generate invoice number
                $invoiceNumber = 'INV-' . $date->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
                
                // Generate random transaction data
                $subtotal = 0;
                $itemCount = 0;
                $itemsPerTransaction = rand(2, 6);
                
                // Hitung subtotal dari items
                for ($k = 0; $k < $itemsPerTransaction; $k++) {
                    $product = $products->random();
                    $quantity = rand(1, 4);
                    $subtotal += $product->price * $quantity;
                    $itemCount += $quantity;
                }
                
                // Hitung tax (10%)
                $tax = $subtotal * 0.1;
                
                // Hitung discount (0-15%)
                $discountRate = rand(0, 15) / 100;
                $discount = $subtotal * $discountRate;
                
                // Hitung final amount
                $finalAmount = $subtotal + $tax - $discount;
                
                // Payment method (cash, qris, transfer)
                $paymentMethods = ['cash', 'qris', 'transfer'];
                $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
                
                // Generate payment amount (lebih besar dari final amount untuk kembalian)
                $paymentAmount = $finalAmount + rand(0, 50000);
                $changeAmount = $paymentAmount - $finalAmount;
                
                // Customer name
                $customerNames = ['Budi', 'Siti', 'Ahmad', 'Rina', 'Dewi', 'Andi', 'Rizky', 'Maya', 'Rudi', 'Lina'];
                $customerName = $customerNames[array_rand($customerNames)] . ' ' . rand(1, 99);
                
                $transaction = Transaction::create([
                    'invoice_number' => $invoiceNumber,
                    'user_id' => $user->id,
                    'customer_name' => $customerName,
                    'total_amount' => $subtotal,
                    'tax' => $tax,
                    'discount' => $discount,
                    'final_amount' => $finalAmount,
                    'item_count' => $itemCount,
                    'payment_amount' => $paymentAmount,
                    'change_amount' => $changeAmount,
                    'payment_method' => $paymentMethod,
                    'payment_code' => $paymentMethod === 'qris' ? strtoupper(bin2hex(random_bytes(8))) : null,
                    'transaction_date' => $date->copy()->addHours(rand(8, 20))->addMinutes(rand(0, 59)),
                    'created_at' => $date->copy()->addHours(rand(8, 20))->addMinutes(rand(0, 59)),
                    'updated_at' => $date->copy()->addHours(rand(8, 20))->addMinutes(rand(0, 59)),
                ]);
                
                // Buat transaction items
                for ($k = 0; $k < $itemsPerTransaction; $k++) {
                    $product = $products->random();
                    $quantity = rand(1, 4);
                    $price = $product->price;
                    $itemSubtotal = $price * $quantity;
                    
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $itemSubtotal,
                    ]);
                }
            }
        }
        
        // Buat transaksi untuk hari ini
        $todayTransactions = rand(5, 10);
        for ($i = 0; $i < $todayTransactions; $i++) {
            $date = Carbon::now();
            
            // Generate invoice number
            $invoiceNumber = 'INV-' . $date->format('Ymd') . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);
            
            // Generate random transaction data
            $subtotal = 0;
            $itemCount = 0;
            $itemsPerTransaction = rand(2, 5);
            
            // Hitung subtotal dari items
            for ($k = 0; $k < $itemsPerTransaction; $k++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $subtotal += $product->price * $quantity;
                $itemCount += $quantity;
            }
            
            // Hitung tax (10%)
            $tax = $subtotal * 0.1;
            
            // Hitung discount (0-10%)
            $discountRate = rand(0, 10) / 100;
            $discount = $subtotal * $discountRate;
            
            // Hitung final amount
            $finalAmount = $subtotal + $tax - $discount;
            
            // Payment method
            $paymentMethods = ['cash', 'qris', 'transfer'];
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
            
            // Generate payment amount
            $paymentAmount = $finalAmount + rand(0, 20000);
            $changeAmount = $paymentAmount - $finalAmount;
            
            // Customer name
            $customerNames = ['Budi', 'Siti', 'Ahmad', 'Rina', 'Dewi', 'Andi', 'Rizky', 'Maya', 'Rudi', 'Lina'];
            $customerName = $customerNames[array_rand($customerNames)] . ' ' . rand(1, 99);
            
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => $user->id,
                'customer_name' => $customerName,
                'total_amount' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'final_amount' => $finalAmount,
                'item_count' => $itemCount,
                'payment_amount' => $paymentAmount,
                'change_amount' => $changeAmount,
                'payment_method' => $paymentMethod,
                'payment_code' => $paymentMethod === 'qris' ? strtoupper(bin2hex(random_bytes(8))) : null,
                'transaction_date' => $date->copy()->subHours(rand(0, 12))->subMinutes(rand(0, 59)),
                'created_at' => $date->copy()->subHours(rand(0, 12))->subMinutes(rand(0, 59)),
                'updated_at' => $date->copy()->subHours(rand(0, 12))->subMinutes(rand(0, 59)),
            ]);
            
            // Buat transaction items
            for ($k = 0; $k < $itemsPerTransaction; $k++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $price = $product->price;
                $itemSubtotal = $price * $quantity;
                
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $itemSubtotal,
                ]);
            }
        }
    }
}