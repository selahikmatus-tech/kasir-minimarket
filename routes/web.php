<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\KasirController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('products', ProductController::class);
    
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::get('/kasir/search', [KasirController::class, 'search'])->name('kasir.search');
    Route::post('/kasir/add-to-cart/{product}', [KasirController::class, 'addToCart'])->name('kasir.add-to-cart');
    Route::delete('/kasir/remove-from-cart/{product}', [KasirController::class, 'removeFromCart'])->name('kasir.remove-from-cart');
    Route::post('/kasir/checkout', [KasirController::class, 'checkout'])->name('kasir.checkout');
    Route::get('/kasir/receipt', [KasirController::class, 'receipt'])->name('kasir.receipt');
    
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
});
