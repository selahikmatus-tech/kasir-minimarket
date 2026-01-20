@extends('layouts.kasir')

@section('title', 'Tambah Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Tambah Barang</h2>
        <p class="text-muted mb-0">Tambahkan produk baru ke dalam sistem</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
        <i class='bx bx-arrow-back me-1'></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class='bx bx-package me-2'></i>Informasi Produk
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="code" class="form-label fw-semibold">
                            <i class='bx bx-barcode me-1 text-primary'></i>Kode Barang
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class='bx bx-barcode'></i>
                            </span>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code') }}" 
                                   placeholder="Masukkan kode barang" required>
                        </div>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            <i class='bx bx-package me-1 text-primary'></i>Nama Barang
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class='bx bx-package'></i>
                            </span>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="Masukkan nama barang" required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="price" class="form-label fw-semibold">
                            <i class='bx bx-money me-1 text-primary'></i>Harga
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold">Rp</span>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price') }}" 
                                   placeholder="0" min="0" step="0.01" required>
                        </div>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="stock" class="form-label fw-semibold">
                            <i class='bx bx-cube me-1 text-primary'></i>Stok
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class='bx bx-cube'></i>
                            </span>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                   id="stock" name="stock" value="{{ old('stock', 0) }}" 
                                   placeholder="0" min="0" required>
                        </div>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class='bx bx-x me-1'></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class='bx bx-save me-1'></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection