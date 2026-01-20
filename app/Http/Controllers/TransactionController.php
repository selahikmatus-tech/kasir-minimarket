<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user', 'items.product');

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('transaction_date', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Search by invoice number or customer name
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%');
            });
        }

        $transactions = $query->latest()->paginate(5);
        $totalRevenue = $query->sum('total_amount');
        
        // Hitung total items yang terjual
        $totalItems = 0;
        foreach ($transactions as $transaction) {
            $totalItems += $transaction->items->sum('quantity');
        }
        
        // Set cart count in session for sidebar
        $cart = session('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));
        session(['cart_count' => $cartCount]);

        return view('transactions.index', compact('transactions', 'totalRevenue', 'totalItems'));
    }

    public function show(string $id)
    {
        $transaction = Transaction::with('user', 'items.product')->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }
}
