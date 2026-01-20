@extends('layouts.kasir')

@section('title', 'Data Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Data Barang</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class='bx bx-plus'></i> Tambah Barang
    </a>
</div>

<!-- Form Pencarian -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('products.index') }}" class="row g-3">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text"><i class='bx bx-search'></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan kode atau nama barang..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary me-2">
                    <i class='bx bx-search me-1'></i>Cari
                </button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                    <i class='bx bx-reset me-1'></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
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
                                @if($product->stock > 0)
                                    <span class="badge bg-success">{{ $product->stock }}</span>
                                @else
                                    <span class="badge bg-danger">Habis</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data barang</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Menampilkan {{ $products->firstItem() }} sampai {{ $products->lastItem() }} dari {{ $products->total() }} data
            </div>
            <div>
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection