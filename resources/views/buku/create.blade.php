<!-- resources/views/buku/create.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<style>
        /* Ensure the body takes up the full viewport */
html, body {
    height: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
}

/* Navbar should stay at the top *

/* Adjust container to prevent content from going under the navbar */
.container {
    margin-top: 100px; /* Ensure space for fixed navbar */
    flex: 1;
}
form {
    padding-top: 50px;
}

/* Footer stays at the bottom */
.footer {
    background-color: #343a40;
    color: white;
    text-align: center;
    padding: 15px 0;
    position: fixed;
    bottom: 0;
    width: 100%;
}


    /* Dropdown Menu Styling */
        .dropdown-menu {
            width: 100% !important;
            max-height: 300px;
            overflow-y: auto;
            border-radius: 8px;
            padding: 10px;
            background-color: var(--bg-color);
            color: var(--text-color);
        }

        /* Row & Column Flexbox Layout */
        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        /* Adjust column width for better spacing */
        .col-3 {
            flex: 0 0 calc(25% - 10px); /* 4 columns per row with spacing */
            max-width: calc(25% - 10px);
            padding: 5px;
        }

        /* Dropdown Item Styling */
        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .dropdown-item:hover {
            background-color: var(--hover-bg-color);
            color: var(--hover-text-color);
        }

        /* Dark Mode & Light Mode Support */
        @media (prefers-color-scheme: dark) {
            :root {
                --bg-color: #222;
                --text-color: #fff;
                --hover-bg-color: #333;
                --hover-text-color: #ddd;
            }
        }

        @media (prefers-color-scheme: light) {
            :root {
                --bg-color: #fff;
                --text-color: #000;
                --hover-bg-color: #f0f0f0;
                --hover-text-color: #333;
            }
        }
</style>
</body>
</html>
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Tambah Buku Baru</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('buku.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="judul_buku" class="form-label">Judul Buku</label>
                <input type="text" name="judul_buku" id="judul_buku" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="pengarang" class="form-label">Pengarang</label>
                <input type="text" name="pengarang" id="pengarang" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="penerbit" class="form-label">Penerbit</label>
                <input type="text" name="penerbit" id="penerbit" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="th_terbit" class="form-label">Tahun Terbit</label>
                <input type="number" name="th_terbit" id="th_terbit" class="form-control" min="1900" max="{{ date('Y') }}" required>
            </div>
        </div>

        <!-- Kategori -->
        <div class="mb-3">
    <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
    <div class="border p-3 rounded bg-theme">
        <div class="row">
            @foreach ($categories as $category)
                <div class="col-md-3">
                    <label class="d-flex align-items-center">
                        <input type="checkbox" name="category_id[]" value="{{ $category->id }}" class="form-check-input">
                        <span>{{ $category->name }}</span>
                    </label>
                </div>
            @endforeach
        </div>
    </div>
</div>

        <!-- <div class="mb-3">
    <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
    <div class="border p-3 rounded bg-theme">
        <div class="row">
        @foreach ($categories as $category)
                <input type="checkbox" name="category_id[]" value="{{ $category->id }}" class="form-check-input">
                <span>{{ $category->name }}</span>
        @endforeach
        </div>
    </div>
    <small class="text-danger d-none" id="category-error">Pilih setidaknya satu kategori.</small>
</div>
    <div class="mb-3">
        <label for="stock" class="form-label">Stok</label>
        <input type="number" name="stock" id="stock" class="form-control" min="1" required>
    </div> -->


    <!-- Tambahkan Tombol Submit -->
    <div class="text-end mt-3">
        <button type="submit" class="btn btn-success">Simpan</button>
    </div>
    <!-- <input type="checkbox" name="category_id[]" value="{{ $category->id }}" class="form-check-input"> -->

</form>
