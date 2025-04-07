<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel Perpustakaan')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/Login.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('scripts')
</head>
<body>
    <!-- nav -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">Laravel Perpustakaan</a>

<!-- shortcut -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-3">
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('peminjaman.index') }}">Peminjaman</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('buku.create') }}">Tambah Buku</a></li>
                </ul>
            </div> 

            {{-- Logout kanan atas --}}
            <div class="d-flex ms-auto">
                <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5 pt-5">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <div id="loading-overlay" style="display: none;
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255, 255, 255, 0.7); z-index: 9999;
        justify-content: center; align-items: center;
        font-size: 1.5rem;">
        <div class="spinner-border text-primary" role="status"></div>
        <span class="ms-3">Loading...</span>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const overlay = document.getElementById("loading-overlay");

            document.querySelectorAll("form").forEach(form => {
                form.addEventListener("submit", function () {
                    overlay.style.display = "flex";
                });
            });

            document.querySelectorAll(".show-loading").forEach(btn => {
                btn.addEventListener("click", function () {
                    overlay.style.display = "flex";
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
