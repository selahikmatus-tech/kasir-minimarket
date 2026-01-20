@extends('layouts.kasir')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Laporan Penjualan</h2>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('transactions.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <label for="search" class="form-label">Pencarian</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Cari invoice atau customer..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class='bx bx-filter'></i> Filter
                    </button>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                        <i class='bx bx-reset'></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Transaksi</h5>
                <h3>{{ $transactions->total() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total Item Terjual</h5>
                <h3>{{ $totalItems }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Total Pendapatan</h5>
                <h3>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Rata-rata per Transaksi</h5>
                <h3>Rp {{ number_format($transactions->total() > 0 ? $totalRevenue / $transactions->total() : 0, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No. Invoice</th>
                                <th>Tanggal</th>
                                <th>Jumlah Item</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $index => $transaction)
                                <tr>
                                    <td>{{ $transactions->firstItem() + $index }}</td>
                                <td><strong class="text-primary">{{ $transaction->invoice_number }}</strong></td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $transaction->item_count }}</td>
                                    <td>
                                        <strong>
                                            Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}
                                        </strong>
                                    </td>

                                    <td>
                                        <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-info">
                                            <i class='bx bx-show'></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data transaksi</td>
                                </tr>
                            @endforelse
                        </tbody>
            </table>
        </div>
        
        <!-- Pagination Info -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Menampilkan {{ $transactions->firstItem() }} sampai {{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi
            </div>
            <div>
                {{ $transactions->links('vendor.pagination.simple-bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection