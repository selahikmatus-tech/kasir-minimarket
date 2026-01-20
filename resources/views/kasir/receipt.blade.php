@extends('layouts.kasir')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-body">

            <h4 class="text-center mb-3">STRUK PEMBAYARAN</h4>

            <p>
                <strong>No Invoice:</strong> {{ $transaction->invoice_number }} <br>
                <strong>Tanggal:</strong> {{ $transaction->transaction_date->format('d/m/Y H:i') }} <br>
                <strong>Kasir:</strong> {{ $transaction->user->name }}
            </p>

            <hr>

            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->price,0,',','.') }}</td>
                            <td>Rp {{ number_format($item->subtotal,0,',','.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <hr>

            <h5 class="text-end">
                Total: Rp {{ number_format($transaction->total_amount,0,',','.') }}
            </h5>

            <p class="text-end">
                Bayar: Rp {{ number_format($transaction->payment_amount,0,',','.') }} <br>
                Kembalian: Rp {{ number_format($transaction->change_amount,0,',','.') }}
            </p>

            <div class="text-center mt-3">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bx bx-printer"></i> Cetak
                </button>
            </div>

        </div>
    </div>
</div>
@endsection
