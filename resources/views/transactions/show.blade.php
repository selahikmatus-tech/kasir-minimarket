@extends('layouts.kasir')

@section('title', 'Detail Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Detail Transaksi</h2>
    <div>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
            <i class='bx bx-arrow-back'></i> Kembali
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <i class='bx bx-printer'></i> Cetak
        </button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Informasi Transaksi</h5>
                <table class="table table-sm">
                    <tr>
                        <td><strong>No. Invoice</strong></td>
                        <td>: <strong class="text-primary">{{ $transaction->invoice_number }}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>No Transaksi</strong></td>
                        <td>: {{ $transaction->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal</strong></td>
                        <td>: {{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kasir</strong></td>
                        <td>: {{ $transaction->user->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah Item</strong></td>
                        <td>: {{ $transaction->item_count }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5>Ringkasan Pembayaran</h5>
                <table class="table table-sm">
                    <tr>
                        <td><strong>Total</strong></td>
                        <td class="text-end"><strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <h5>Detail Item</h5>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->product->code }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
@media print {
    .sidebar, .topbar, .btn {
        display: none !important;
    }
    .content-wrapper {
        margin: 0 !important;
        padding: 0 !important;
    }
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
}
</style>
@endpush