<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    /**
     * Halaman kasir
     */
    public function index()
    {
        $products = collect();
        $cart = Session::get('cart', []);

        $cartCount = array_sum(array_column($cart, 'quantity'));
        $cartTotal = array_sum(array_map(
            fn ($item) => $item['quantity'] * $item['price'],
            $cart
        ));

        // Ambil nomor invoice terakhir
        $lastTransaction = Transaction::latest()->first();
        $lastInvoiceNumber = $lastTransaction
            ? (int) substr($lastTransaction->invoice_number, -4)
            : 0;

        return view('kasir.index', compact(
            'products',
            'cart',
            'cartCount',
            'cartTotal',
            'lastInvoiceNumber'
        ));
    }

    /**
     * Cari produk
     */
    public function search(Request $request)
    {
        $search = $request->search;

        $products = Product::where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            })
            ->where('stock', '>', 0)
            ->get();

        $cart = Session::get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));
        $cartTotal = array_sum(array_map(
            fn ($item) => $item['quantity'] * $item['price'],
            $cart
        ));

        $lastTransaction = Transaction::latest()->first();
        $lastInvoiceNumber = $lastTransaction
            ? (int) substr($lastTransaction->invoice_number, -4)
            : 0;

        return view('kasir.index', compact(
            'products',
            'cart',
            'cartCount',
            'cartTotal',
            'lastInvoiceNumber'
        ));
    }

    /**
     * Tambah ke keranjang
     */
    public function addToCart(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $qty = $request->quantity ?? 1;

        if ($product->stock < $qty) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $qty;
        } else {
            $cart[$productId] = [
                'product_id' => $product->id, // ✅ TAMBAHKAN
                'code'       => $product->code,
                'name'       => $product->name,
                'price'      => $product->price,
                'quantity'   => $qty,
            ];

        }

        Session::put('cart', $cart);

        return back()->with('success', 'Barang ditambahkan ke keranjang');
    }

    /**
     * Hapus dari keranjang
     */
    public function removeFromCart($productId)
    {
        $cart = Session::get('cart', []);
        unset($cart[$productId]);

        Session::put('cart', $cart);

        return back()->with('success', 'Barang dihapus dari keranjang');
    }

    /**
     * Checkout
     */
    public function checkout(Request $request)
{
    $cart = session('cart', []);

    if (empty($cart)) {
        return redirect()->back()->with('error', 'Keranjang kosong');
    }

    DB::beginTransaction();
    try {
        $totalAmount = 0;
        $itemCount   = 0;

        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
            $itemCount   += $item['quantity'];
        }

        $tax        = 0; // kalau ada pajak ubah disini
        $discount   = 0; // kalau ada diskon ubah disini
        $finalAmount = $totalAmount + $tax - $discount;

        // 🔥 SIMPAN TRANSAKSI
        $transaction = Transaction::create([
            'invoice_number'  => 'INV-' . time(),
            'total_amount'    => $totalAmount,
            'tax'             => $tax,
            'discount'        => $discount,
            'final_amount'    => $finalAmount,
            'item_count'      => $itemCount,
            'payment_amount'  => $request->payment_amount ?? $finalAmount,
            'change_amount'   => ($request->payment_amount ?? $finalAmount) - $finalAmount,
            'payment_method'  => $request->payment_method ?? 'cash',
            'payment_code'    => $request->payment_code,
            'customer_name'   => $request->customer_name,
            'user_id'         => Auth::id(),
        ]);

        // 🔥 SIMPAN ITEM
        foreach ($cart as $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id'     => $item['product_id'],
                'quantity'       => $item['quantity'],
                'price'          => $item['price'],
                'subtotal'       => $item['price'] * $item['quantity'],
            ]);
        }

        session()->forget('cart');
        DB::commit();

        return redirect()->route('transactions.show', $transaction->id)
            ->with('success', 'Transaksi berhasil');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', $e->getMessage());
    }
}

    /**
     * Halaman struk / receipt
     */
    public function receipt()
    {
        $transaction = Session::get('last_transaction');

        if (!$transaction) {
            return redirect()->route('kasir.index');
        }

        $transaction->load(['user', 'items.product']);

        return view('kasir.receipt', compact('transaction'));
    }
}
