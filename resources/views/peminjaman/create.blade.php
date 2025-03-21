@extends('layouts.app')
<nav class="navbar navbar-dark bg-dark">
    <div class="container d-flex justify-content-between align-items-left">
        <a href="{{ url()->previous() }}" class="btn btn-light me-auto">🔙 Kembali</a>
        <!-- <a class="navbar-brand" href="{{ route('dashboard') }}">Laravel Perpustakaan</a> -->
        <!-- <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a> -->
    </div>
</nav>

@section('title', 'Buat Peminjaman Baru')

@section('content')
<div class="container">
    <h2 class="mb-3">Buat Peminjaman Baru</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('peminjaman.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="id_buku" class="form-label">Pilih Buku</label>
            <select name="id_buku" id="id_buku" class="form-control" required>
                <option value="">-- Pilih Buku --</option>
                @foreach ($buku as $book)
                    <option value="{{ $book->id }}">{{ $book->judul_buku }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="id" class="form-label">Pilih Peminjam</label>
            <select name="id" id="id" class="form-control" required>
                <option value="">-- Pilih Peminjam --</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tgl_pinjam" class="form-label">Tanggal Pinjam</label>
            <input type="date" name="tgl_pinjam" id="tgl_pinjam" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="tgl_kembali" class="form-label">Tanggal Kembali</label>
            <input type="date" name="tgl_kembali" id="tgl_kembali" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection