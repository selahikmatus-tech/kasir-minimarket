@extends('layouts.app')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
    <div class="row justify-content-center w-100">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                                    <i class='bx bx-store' style="font-size: 3rem; color: #007bff;"></i>
                                </div>
                        <h2 class="fw-bold text-primary">Kasir Toko</h2>
                        <p class="text-muted">Silakan login untuk melanjutkan</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                <i class='bx bx-envelope me-1'></i> Email
                            </label>
                            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                   placeholder="Masukkan email Anda">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">
                                <i class='bx bx-lock me-1'></i> Password
                            </label>
                            <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="current-password"
                                   placeholder="Masukkan password Anda">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Ingat saya
                                </label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); border: none;">
                                <i class='bx bx-log-in me-2'></i> Login
                            </button>
                        </div>
                    </form>

                    <div class="mt-4 text-center">
                        <small class="text-muted">
                            <i class='bx bx-info-circle me-1'></i>
                            Login default: admin@kasir.com / password123
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
body {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.card {
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

.btn-primary:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}
</style>
@endpush
