@extends('layouts.kasir')

@section('title', 'Edit Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Barang</h2>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
        <i class='bx bx-arrow-back'></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode Barang</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code', $product->code) }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price', $product->price) }}" 
                                   min="0" step="0.01" required>
                        </div>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stok</label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                               id="stock" name="stock" value="{{ old('stock', $product->stock) }}" 
                               min="0" required>
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
                    <i class='bx bx-save me-1'></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection