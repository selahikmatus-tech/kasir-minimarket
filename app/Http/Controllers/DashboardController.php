<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalTransactions = Transaction::count();
        $totalRevenue = Transaction::sum('final_amount'); // Update ke final_amount
        
        // Set cart count in session for sidebar
        $cart = session('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));
        session(['cart_count' => $cartCount]);
        
        // Get transactions for the last 7 days
        $weeklyTransactions = Transaction::select(
            DB::raw('DATE(transaction_date) as date'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(final_amount) as total') // Update ke final_amount
        )
        ->where('transaction_date', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return view('dashboard', compact('totalProducts', 'totalTransactions', 'totalRevenue', 'weeklyTransactions'));
    }
}
