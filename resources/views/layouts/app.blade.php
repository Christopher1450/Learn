<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel Perpustakaan')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/Login.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Laravel Perpustakaan</a>
            <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
            <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        </div>
    </nav>
    <div class="container mt-4" style="padding-top: 80px;">
        @yield('content')
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
        <style>
            .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #343a40;
            padding: 0px 5px;
            color: white;
            z-index: 1;
     }
     /* .container {
    padding-top: 0px 10px; /* Biarkan kontennya turun supaya nggak ketabrak navbar */
    /* } */
        /* .navbar {
            background-color: #343a40;
            padding: 10px 20px;
            top: 0;
            position: top;
        } */
        </style>
        <div id="loading-overlay" style="display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255, 255, 255, 0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
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

        // Untuk semua tombol ada loadingnya
                document.querySelectorAll(".show-loading").forEach(btn => {
                    btn.addEventListener("click", function () {
                        overlay.style.display = "flex";
                    });
                });
            });
        </script>
