@extends('layouts.kasir')

@section('content')
<div class="container">
    <h3 class="mb-4">📊 Dashboard Kasir</h3>

    {{-- STATISTIC CARDS (Tetap sama) --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Produk</h6>
                    <h4 class="fw-bold">{{ $totalProducts }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Transaksi</h6>
                    <h4 class="fw-bold">{{ $totalTransactions }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Pendapatan</h6>
                    <h4 class="fw-bold text-primary">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- WEEKLY TRANSACTIONS WITH NAVIGATION --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">📈 Pendapatan Harian</h6>
            <div>
                {{-- Tombol Minggu Sebelumnya --}}
                <a href="{{ route('dashboard', ['week_offset' => $weekOffset + 1]) }}" class="btn btn-sm btn-outline-secondary">
                    &laquo; Minggu Sebelumnya
                </a>
                
                @if($weekOffset > 0)
                    {{-- Tombol Minggu Selanjutnya (Hanya tampil jika bukan minggu ini) --}}
                    <a href="{{ route('dashboard', ['week_offset' => $weekOffset - 1]) }}" class="btn btn-sm btn-outline-secondary">
                        Minggu Selanjutnya &raquo;
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4">Tanggal</th>
                            <th class="px-4">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($weeklyTransactions as $item)
                            <tr>
                                <td class="px-4">{{ $item['date'] }}</td>
                                <td class="px-4 fw-bold text-success">
                                    Rp {{ number_format($item['total'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center py-4 text-muted">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection