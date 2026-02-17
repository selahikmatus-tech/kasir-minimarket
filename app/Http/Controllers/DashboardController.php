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
        Carbon::setLocale('id');

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

            if ($totalPerHari > 0) {
                $weeklyTransactions[] = [
                    'date'  => $date->translatedFormat('l, d F Y'), // Contoh: Senin, 13 Januari 2025
                    'total' => $totalPerHari
                ];
            }
        }

        // Membalik urutan agar tanggal terbaru muncul di bawah (seperti screenshot Anda)
        // Jika ingin tanggal terbaru di atas, hapus baris array_reverse ini.
        $weeklyTransactions = array_reverse($weeklyTransactions);

        // 5. Data Pendapatan Bulanan (Tahun Ini)
        $currentYear = date('Y');
        $monthlyTransactions = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthStart = Carbon::create($currentYear, $m, 1)->startOfMonth();
            $monthEnd   = Carbon::create($currentYear, $m, 1)->endOfMonth();

            $totalPerBulan = Transaction::whereBetween('created_at', [$monthStart, $monthEnd])
                                        ->sum('final_amount');

            if ($totalPerBulan > 0) {
                $monthlyTransactions[] = [
                    'month' => $monthStart->translatedFormat('F Y'), // Contoh: Januari 2025
                    'total' => $totalPerBulan
                ];
            }
        }
        
        // Urutkan bulan terbaru di atas (opsional, tapi biasanya rekap tahunan urut bulan Jan-Des atau sebaliknya)
        // Kita biarkan urut Jan -> Des (default loop) atau dibalik jika ingin Des -> Jan
        // $monthlyTransactions = array_reverse($monthlyTransactions);

        return view('dashboard', compact(
            'totalProducts', 
            'totalTransactions', 
            'totalIncome', 
            'weeklyTransactions',
            'weekOffset',
            'monthlyTransactions'
        ));
    }

    public function printDaily(Request $request)
    {
        Carbon::setLocale('id');
        $weekOffset = (int) $request->get('week_offset', 0);
        
        $endDate   = Carbon::today()->subWeeks($weekOffset);
        $startDate = (clone $endDate)->subDays(6);

        $weeklyTransactions = [];

        for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
            $totalPerHari = Transaction::whereDate('created_at', $date->format('Y-m-d'))
                                     ->sum('final_amount');

            if ($totalPerHari > 0) {
                $weeklyTransactions[] = [
                    'date'  => $date->translatedFormat('l, d F Y'),
                    'total' => $totalPerHari
                ];
            }
        }
        $weeklyTransactions = array_reverse($weeklyTransactions);

        return view('reports.daily', compact('weeklyTransactions', 'startDate', 'endDate'));
    }

    public function printMonthly(Request $request)
    {
        Carbon::setLocale('id');
        $currentYear = date('Y');
        $monthlyTransactions = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthStart = Carbon::create($currentYear, $m, 1)->startOfMonth();
            $monthEnd   = Carbon::create($currentYear, $m, 1)->endOfMonth();

            $totalPerBulan = Transaction::whereBetween('created_at', [$monthStart, $monthEnd])
                                        ->sum('final_amount');

            if ($totalPerBulan > 0) {
                $monthlyTransactions[] = [
                    'month' => $monthStart->translatedFormat('F Y'),
                    'total' => $totalPerBulan
                ];
            }
        }

        return view('reports.monthly', compact('monthlyTransactions', 'currentYear'));
    }
}