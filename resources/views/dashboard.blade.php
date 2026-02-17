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

    <div class="row">
        {{-- WEEKLY TRANSACTIONS WITH NAVIGATION --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">📈 Pendapatan Perhari</h6>
                    <div>
                        {{-- Tombol Cetak --}}
                        <a href="{{ route('report.daily', ['week_offset' => $weekOffset]) }}" target="_blank" class="btn btn-sm btn-primary me-1">
                            <i class='bx bx-printer'></i> Cetak
                        </a>

                        {{-- Tombol Minggu Sebelumnya --}}
                        <a href="{{ route('dashboard', ['week_offset' => $weekOffset + 1]) }}" class="btn btn-sm btn-outline-secondary">
                            &laquo; Prev
                        </a>
                        
                        @if($weekOffset > 0)
                            {{-- Tombol Minggu Selanjutnya (Hanya tampil jika bukan minggu ini) --}}
                            <a href="{{ route('dashboard', ['week_offset' => $weekOffset - 1]) }}" class="btn btn-sm btn-outline-secondary">
                                Next &raquo;
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3">Tanggal</th>
                                    <th class="px-4 py-3 text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $maxTotal = 0;
                                    foreach($weeklyTransactions as $t) {
                                        if($t['total'] > $maxTotal) $maxTotal = $t['total'];
                                    }
                                @endphp
                                @forelse ($weeklyTransactions as $item)
                                    @php
                                        $percent = $maxTotal > 0 ? ($item['total'] / $maxTotal) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium text-dark">{{ $item['date'] }}</span>
                                                @if($item['total'] > 0)
                                                    <div class="progress mt-1" style="height: 6px; width: 100px; background-color: #e9ecef;">
                                                        <div class="progress-bar bg-primary" role="progressbar" 
                                                             style="width: {{ $percent }}%; border-radius: 3px;" 
                                                             aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-end">
                                            <span class="fw-bold {{ $item['total'] > 0 ? 'text-primary' : 'text-muted' }}">
                                                Rp {{ number_format($item['total'], 0, ',', '.') }}
                                            </span>
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

        {{-- MONTHLY TRANSACTIONS --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">📅 Pendapatan Bulanan ({{ date('Y') }})</h6>
                    <a href="{{ route('report.monthly') }}" target="_blank" class="btn btn-sm btn-primary">
                        <i class='bx bx-printer'></i> Cetak
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3">Bulan</th>
                                    <th class="px-4 py-3 text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $maxTotalMonthly = 0;
                                    foreach($monthlyTransactions as $m) {
                                        if($m['total'] > $maxTotalMonthly) $maxTotalMonthly = $m['total'];
                                    }
                                @endphp
                                @forelse ($monthlyTransactions as $item)
                                    @php
                                        $percent = $maxTotalMonthly > 0 ? ($item['total'] / $maxTotalMonthly) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium text-dark">{{ $item['month'] }}</span>
                                                <div class="progress mt-1" style="height: 6px; width: 100%; background-color: #e9ecef;">
                                                    <div class="progress-bar bg-success" role="progressbar" 
                                                         style="width: {{ $percent }}%; border-radius: 3px;" 
                                                         aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-end">
                                            <span class="fw-bold text-success">
                                                Rp {{ number_format($item['total'], 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-4 text-muted">Belum ada transaksi bulan ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection