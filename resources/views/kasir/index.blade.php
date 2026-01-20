@extends('layouts.kasir')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 text-gray-800 mb-0">Halaman Kasir</h1>
                    <small class="text-muted">Sistem Point of Sale</small>
                </div>
                <div class="text-end bg-light p-3 rounded">
                    <small class="text-muted d-block">Nomor Invoice:</small>
                    <h4 class="text-primary fw-bold mb-0">
                        <i class='bx bx-receipt me-1'></i>
                        {{ sprintf('INV-%s-%04d', date('Ymd'), ($lastInvoiceNumber + 1)) }}
                    </h4>
                    <small class="text-muted">{{ date('d/m/Y H:i') }}</small>
                </div>
            </div>
            
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class='bx bx-check-circle me-2'></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class='bx bx-error-circle me-2'></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Print Receipt Button -->
            @if(Session::has('last_transaction'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class='bx bx-info-circle me-2'></i>
                    Transaksi terakhir berhasil! 
                    <a href="{{ route('kasir.receipt') }}" class="alert-link">
                        <i class='bx bx-printer me-1'></i>Cetak Struk
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="row">
                <!-- Product Search Section -->
                <div class="col-lg-7">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="m-0 font-weight-bold">
                                        <i class='bx bx-search me-2'></i>Cari Barang
                                    </h6>
                                  
                                </div>
                                <div class="text-end">
                                    <small class="text-white-50">Kasir: {{ auth()->user()->name }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="searchForm" method="GET" action="{{ route('kasir.search') }}">
                                <div class="input-group">
                                    <input type="text" 
                                           name="search" 
                                           id="searchInput"
                                           class="form-control" 
                                           placeholder="Masukkan kode atau nama barang..." 
                                           value="{{ request('search') }}"
                                           autocomplete="off">
                                    <button class="btn btn-primary" type="submit">
                                        <i class='bx bx-search'></i> Cari
                                    </button>
                                </div>
                            </form>
                            
                            <!-- Search Results -->
                            <div id="searchResults" class="mt-3">
                                @if(request('search'))
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Kode</th>
                                                    <th>Nama Barang</th>
                                                    <th>Harga</th>
                                                    <th>Stok</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($products as $product)
                                                    <tr>
                                                        <td>{{ $product->code }}</td>
                                                        <td>{{ $product->name }}</td>
                                                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                                                {{ $product->stock }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <form method="POST" action="{{ route('kasir.add-to-cart', $product->id) }}" class="d-inline">
                                                                @csrf
                                                                <div class="input-group input-group-sm">
                                                                    <input type="number" 
                                                                           name="quantity" 
                                                                           class="form-control" 
                                                                           value="1" 
                                                                           min="1" 
                                                                           max="{{ $product->stock }}"
                                                                           style="width: 60px;">
                                                                    <button type="submit" 
                                                                            class="btn btn-success btn-sm"
                                                                            {{ $product->stock == 0 ? 'disabled' : '' }}>
                                                                        <i class='bx bx-cart-add'></i>
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted">
                                                            <i class='bx bx-search-alt-2 fs-1'></i><br>
                                                            Barang tidak ditemukan
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Cart Section -->
                <div class="col-lg-5">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-success text-white">
                            <h6 class="m-0 font-weight-bold">
                                <i class='bx bx-cart me-2'></i>Keranjang Belanja
                                <span class="badge bg-light text-dark ms-2">{{ $cartCount ?? 0 }} item</span>
                            </h6>
                        </div>
                        <div class="card-body">
                            @if(session('cart') && count(session('cart')) > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Barang</th>
                                                <th>Qty</th>
                                                <th>Harga</th>
                                                <th>Subtotal</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(session('cart') as $id => $item)
                                                <tr>
                                                    <td>
                                                        <small class="d-block">{{ $item['name'] }}</small>
                                                        <small class="text-muted">{{ $item['code'] }}</small>
                                                    </td>
                                                    <td>{{ $item['quantity'] }}</td>
                                                    <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($item['quantity'] * $item['price'], 0, ',', '.') }}</td>
                                                    <td>
                                                        <form method="POST" action="{{ route('kasir.remove-from-cart', $id) }}" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class='bx bx-trash'></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <hr>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Total:</h5>
                                    <h4 class="mb-0 text-primary">Rp {{ number_format($cartTotal ?? 0, 0, ',', '.') }}</h4>
                                </div>
                                
                                <form method="POST" action="{{ route('kasir.checkout') }}" id="checkout-form">
                                    @csrf
                                    <input type="hidden" name="payment_method" value="cash">
                                   
                                    
                                    <div class="mb-3" id="payment_amount_container">
                                        <label for="payment_amount" class="form-label">Jumlah Bayar</label>
                                        <input type="number" 
                                               name="payment_amount" 
                                               id="payment_amount"
                                               class="form-control" 
                                               placeholder="Masukkan jumlah bayar"
                                               min="{{ $cartTotal ?? 0 }}"
                                               step="100"
                                               required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="change_amount" class="form-label">Kembalian</label>
                                        <input type="text" 
                                               id="change_amount"
                                               class="form-control" 
                                               readonly
                                               placeholder="0">
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class='bx bx-check-circle me-2'></i>Proses Transaksi
                                    </button>
                                </form>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class='bx bx-cart fs-1'></i>
                                    <p class="mt-2 mb-0">Keranjang masih kosong</p>
                                    <small>Tambahkan barang untuk memulai transaksi</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Calculate change automatically
    document.getElementById('payment_amount').addEventListener('input', function() {
        const total = {{ $cartTotal ?? 0 }};
        const payment = parseFloat(this.value) || 0;
        const change = payment - total;
        
        document.getElementById('change_amount').value = change >= 0 
            ? 'Rp ' + change.toLocaleString('id-ID') 
            : 'Uang tidak cukup';
    });
    
    // Script yang tidak perlu dihapus karena payment method sudah dihapus
    
    // Auto-submit search after 500ms of typing
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (this.value.length >= 2) {
                document.getElementById('searchForm').submit();
            }
        }, 500);
    });
</script>
@endpush
@endsection