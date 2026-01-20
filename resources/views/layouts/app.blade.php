<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kasir Toko') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Nunito', sans-serif;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.25rem;
            color: #007bff !important;
        }
        .navbar-brand:hover {
            color: #0056b3 !important;
        }
        .navbar {
            border-bottom: 1px solid #e9ecef;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
        }
        .dropdown-toggle {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .dropdown-toggle:hover {
            background-color: rgba(0, 123, 255, 0.1);
            border-radius: 8px;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            margin-top: 0.5rem;
        }
        .dropdown-item {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            margin: 0.25rem;
            transition: all 0.2s ease;
        }
        .dropdown-item:hover {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            color: #007bff;
            transform: translateX(2px);
        }
        .dropdown-header {
            font-weight: 600;
            color: #495057;
        }
        .navbar {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
            border-bottom: 1px solid #e9ecef;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.25rem;
            color: #007bff !important;
            transition: all 0.3s ease;
        }
        .navbar-brand:hover {
            color: #0056b3 !important;
            transform: translateY(-1px);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class='bx bx-store me-2'></i>{{ config('app.name', 'Kasir Toko') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <!-- Search Box -->
                        <li class="nav-item d-flex align-items-center">
                            <form class="d-flex" action="{{ route('transactions.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" name="search" placeholder="Cari transaksi..." value="{{ request('search') }}">
                                    <button class="btn btn-outline-primary btn-sm" type="submit">
                                        <i class='bx bx-search'></i>
                                    </button>
                                </div>
                            </form>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @auth
                        <!-- User Menu -->
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class='bx bx-user-circle me-2' style="font-size: 1.2rem;"></i>
                                <span class="fw-medium">{{ Auth::user()->name }}</span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end border-0 shadow-sm" style="border-radius: 10px; min-width: 200px; animation: fadeIn 0.2s ease;">
                                <div class="dropdown-header text-center bg-light rounded-top py-3">
                                    <i class='bx bx-user-circle d-block mx-auto mb-2' style="font-size: 2rem; color: #007bff;"></i>
                                    <h6 class="mb-1 fw-bold">{{ Auth::user()->name }}</h6>
                                    <small class="text-muted">{{ Auth::user()->email }}</small>
                                </div>
                                <div class="dropdown-divider my-0"></div>
                                <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    <i class='bx bx-log-out me-2'></i>
                                    <span>Logout</span>
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @else
                        <!-- Login Link -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class='bx bx-log-in me-1'></i>Login
                            </a>
                        </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
