@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: auto; margin-bottom: 25px;">
    <h3>Detail Buku</h3>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <p><strong>Judul:</strong> {{ $buku->judul_buku }}</p>
            <p><strong>Kode Buku:</strong> {{ $buku->id_buku }}</p>
            <p><strong>Pengarang:</strong> {{ $buku->pengarang }}</p>
            <p><strong>Penerbit:</strong> {{ $buku->penerbit }}</p>
            <p><strong>Tahun Terbit:</strong> {{ $buku->th_terbit }}</p>
            <p><strong>Stok:</strong> {{ $buku->stock }}</p>
            <p><strong>Status:</strong> {{ ucfirst($buku->status) }}</p>
            <p><strong>Kategori:</strong> {{ $buku->categories->pluck('name')->implode(', ') }}</p>
        </div>
    </div>

    <!-- {{-- TABEL UNIT STOK --}}
    <div class="card shadow-sm">
        <div class="card-body">
        <h4>Daftar Unit Buku</h4> -->
        <table class="table">
    <thead>
        <tr>
            <th>Buku</th>
            <th>Nama Peminjam</th>
            <th>Kode Unit</th> <!-- Tambahkan ini -->
            <th>Tanggal Pinjam</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($borrowings as $borrowing)
<tr>
    <td>{{ $borrowing->buku->judul_buku ?? '-' }}</td>
    <td>{{ $borrowing->borrower_name }}</td>
    <td>{{ $borrowing->units->kode_buku ?? '-' }}</td>
    <td>{{ $borrowing->borrow_date }}</td>
    <td>
        @if ($borrowing->returned_at)
            <span class="badge bg-success">Dikembalikan</span>
        @else
            <span class="badge bg-warning">Belum Dikembalikan</span>
        @endif
    </td>
</tr>
@endforeach

    </tbody>
</table>
