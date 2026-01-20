<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index(Request $request)
{
    $query = Transaction::with('user', 'items.product');

    if ($request->filled(['start_date', 'end_date'])) {
        $query->whereBetween('created_at', [
            $request->start_date . ' 00:00:00',
            $request->end_date . ' 23:59:59'
        ]);
    }

    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('invoice_number', 'like', "%{$request->search}%")
              ->orWhere('customer_name', 'like', "%{$request->search}%");
        });
    }

    // 🔥 HITUNG SEBELUM paginate
    $totalRevenue = (clone $query)->sum('final_amount');
    $totalItems   = (clone $query)->sum('item_count');

    $transactions = $query->latest()->paginate(5);

    return view('transactions.index', compact(
        'transactions',
        'totalRevenue',
        'totalItems'
    ));
}


    public function show(string $id)
    {
        $transaction = Transaction::with('user', 'items.product')->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }
}
