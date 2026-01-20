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
                'code'     => $product->code,
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => $qty,
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
        $request->validate([
            'payment_amount' => 'required|numeric|min:0',
        ]);

        $cart = Session::get('cart');
        if (!$cart || count($cart) === 0) {
            return back()->with('error', 'Keranjang masih kosong');
        }

        $total = array_sum(array_map(
            fn ($item) => $item['quantity'] * $item['price'],
            $cart
        ));

        if ($request->payment_amount < $total) {
            return back()->with('error', 'Uang pembayaran kurang');
        }

        DB::beginTransaction();

        try {
            // Generate invoice unik
            $last = Transaction::latest()->first();
            $number = $last
                ? (int) substr($last->invoice_number, -4) + 1
                : 1;

            $invoice = 'INV-' . date('Ymd') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

            $transaction = Transaction::create([
                'invoice_number'  => $invoice,
                'user_id'         => Auth::id(),
                'customer_name'   => 'Umum',
                'total_amount'    => $total,
                'payment_amount'  => $request->payment_amount,
                'change_amount'   => $request->payment_amount - $total,
                'payment_method'  => 'cash',
                'transaction_date'=> now(),
            ]);

            foreach ($cart as $productId => $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $productId,
                    'quantity'       => $item['quantity'],
                    'price'          => $item['price'],
                    'subtotal'       => $item['quantity'] * $item['price'],
                ]);

                Product::find($productId)
                    ->decrement('stock', $item['quantity']);
            }

            DB::commit();

            Session::forget('cart');
            Session::put('last_transaction', $transaction);

            return redirect()->route('kasir.receipt');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
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
