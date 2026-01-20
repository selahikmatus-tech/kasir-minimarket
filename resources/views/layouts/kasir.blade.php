<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Kasir Sella</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Boxicons --}}
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fc;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: linear-gradient(180deg, #0d6efd, #0b5ed7);
            color: #fff;
            position: fixed;
            display: flex;
            flex-direction: column;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
        }

        .content-wrapper {
            margin-left: 250px;
            padding: 30px;
        }
    </style>
</head>
<body>

{{-- ===== SIDEBAR ===== --}}
<div class="sidebar p-3">

    {{-- LOGO & USER --}}
    <div class="text-center mb-4">
        <i class="bx bx-store fs-1"></i>
        <h5 class="mt-2 mb-0">Toko Kasir Sella</h5>
        <small class="text-white-50">Sistem Kasir Modern</small>
        <hr class="text-white">
        <small>{{ auth()->user()->name }}</small><br>
        <small class="text-white-50">{{ auth()->user()->email }}</small>
    </div>

    {{-- MENU --}}
    <ul class="nav nav-pills flex-column gap-2">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link">
                <i class="bx bx-home me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('products.index') }}" class="nav-link">
                <i class="bx bx-box me-2"></i> Data Barang
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('kasir.index') }}" class="nav-link">
                <i class="bx bx-cart me-2"></i> Kasir
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('transactions.index') }}" class="nav-link">
                <i class="bx bx-receipt me-2"></i> Laporan Penjualan
            </a>
        </li>
    </ul>

    {{-- LOGOUT BAWAH --}}
    <div class="mt-auto pt-3">
        <hr class="text-white">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger w-100">
                <i class="bx bx-log-out me-2"></i> Logout
            </button>
        </form>
    </div>

</div>

{{-- ===== CONTENT ===== --}}
<div class="content-wrapper">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

</body>
</html>
