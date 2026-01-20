@extends('layouts.kasir')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card card-stats text-white" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title">Total Barang</h4>
                        <h2 class="mb-0">{{ $totalProducts }}</h2>
                    </div>
                    <div class="icon">
                        <i class='bx bx-package' style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card card-stats bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title">Total Transaksi</h4>
                        <h2 class="mb-0">{{ $totalTransactions }}</h2>
                    </div>
                    <div class="icon">
                        <i class='bx bx-receipt' style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card card-stats bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title">Total Pendapatan</h4>
                        <h2 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                    </div>
                    <div class="icon">
                        <i class='bx bx-money' style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Grafik Penjualan 7 Hari Terakhir</h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const weeklyData = @json($weeklyTransactions);
    
    const labels = weeklyData.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    });
    
    const data = weeklyData.map(item => parseFloat(item.total));
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan',
                data: data,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush