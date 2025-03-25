<!-- resources/views/buku/create.blade.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head> -->
@extends('layouts.app')
<div class="container" style="padding-top: 300px; padding-bottom: 10px;">
@section('content')
<div class="container mt-5" style="max-width: 800px;">
    <h2 class="mb-4 fw-bold text-center">Tambah Buku Baru</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('buku.store') }}" method="POST">
        @csrf

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
                <input 
                type="text" 
                name="th_terbit" 
                id="th_terbit" 
                class="form-control" 
                maxlength="4"
                pattern="\d{4}" 
                title="Masukkan 4 digit tahun" 
                required>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Stok</label>
            <input type="number" name="stock" id="stock" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
        <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
        <div class="border p-3 rounded bg-theme" style="max-height: 300px; overflow-y: auto;">
            <div class="row">
                @foreach ($categories as $category)
                    <div class="col-3 mb-2">
                        <label class="d-flex align-items-center">
                            <input type="checkbox" name="category_id[]" value="{{ $category->id }}" class="form-check-input me-2">
                            <span>{{ $category->name }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection

            <!-- buat estetik dikit Th_terbit kalau lebih dri max bisa hilang -->
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tahunInput = document.getElementById("th_terbit");

            tahunInput.addEventListener("input", function () {
                let value = tahunInput.value;

                value = value.replace(/\D/g, "");

                if (value.length > 4) {
                    value = value.slice(-4);
                }

                tahunInput.value = value;
            });
        });
        </script>
