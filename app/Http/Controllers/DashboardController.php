<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil parameter offset minggu (default 0 = minggu ini)
        $weekOffset = (int) $request->get('week_offset', 0);

        // 2. Statistik Utama (Tetap total keseluruhan dari awal)
        $totalProducts     = Product::count();
        $totalTransactions = Transaction::count();
        $totalIncome       = Transaction::sum('final_amount');

        // 3. Tentukan rentang 7 hari berdasarkan offset
        // Jika offset 1, maka mundur 7 hari dari hari ini.
        $endDate   = Carbon::today()->subWeeks($weekOffset);
        $startDate = (clone $endDate)->subDays(6);

        $weeklyTransactions = [];

        // 4. Loop untuk mengambil data per hari dalam rentang tersebut
        for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
            $totalPerHari = Transaction::whereDate('created_at', $date->format('Y-m-d'))
                                     ->sum('final_amount');

            $weeklyTransactions[] = [
                'date'  => $date->translatedFormat('d M'), // Contoh: 13 Jan
                'total' => $totalPerHari
            ];
        }

        // Membalik urutan agar tanggal terbaru muncul di bawah (seperti screenshot Anda)
        // Jika ingin tanggal terbaru di atas, hapus baris array_reverse ini.
        $weeklyTransactions = array_reverse($weeklyTransactions);

        return view('dashboard', compact(
            'totalProducts', 
            'totalTransactions', 
            'totalIncome', 
            'weeklyTransactions',
            'weekOffset'
        ));
    }
}