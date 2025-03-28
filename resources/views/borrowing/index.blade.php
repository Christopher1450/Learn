@php
    use Carbon\Carbon;
@endphp

@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">List Peminjaman Buku</h2>

    <a href="{{ route('peminjaman.create') }}" class="btn btn-primary mb-3">Buat Peminjaman Baru</a>

    <table class="table table-bordered shadow-sm">
        <thead>
            <tr>
                <th>Buku</th>
                <th>Nama Pengakses</th>
                <th>Nama Peminjam</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Durasi Peminjaman</th>
                <th>Fee</th>
                <th>Denda</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($borrowings as $borrowing)
        <tr>
            <td>{{ $borrowing->buku->judul_buku }}</td>
            <td>{{ $borrowing->user->name }}</td>
            <td>{{ $borrowing->borrower?->borrower_name }}</td>
            <td>{{ $borrowing->borrow_date ? \Carbon\Carbon::parse($borrowing->borrow_date)->format('Y-m-d') : '-' }}</td>
            <td>{{ $borrowing->returned_at ? \Carbon\Carbon::parse($borrowing->returned_at)->format('Y-m-d') : '-' }}</td>
            <td>
                @php
                    $durasi = Carbon::parse($borrowing->borrow_date)->diffInDays(Carbon::parse($borrowing->return_date));
                    $terlambat = Carbon::parse($borrowing->return_date)->diffInDays(Carbon::now(), false);
                @endphp
                @if (!$borrowing->isReturned() && $terlambat > 0)
                    <span class="text-danger">Terlambat {{ $terlambat }} hari</span>
                @else
                    {{ $durasi }} Hari
                @endif
            </td>
            <td>Rp {{ number_format($borrowing->fee ?? 0, 0, ',', '.') }}</td>
                <td>
                    @if ($borrowing->penalty > 0)
                        <span class="text-danger">Rp {{ number_format($borrowing->penalty, 0, ',', '.') }}</span>
                    @else
                        -
                    @endif
                </td>
            <td>
                @if ($borrowing->isReturned())
                    <span class="badge bg-success">Dikembalikan</span>
                @else
                    <span class="badge bg-warning text-dark">Belum Dikembalikan</span>
                @endif
            </td>
            <td class="d-flex gap-2">
                @if (!$borrowing->isReturned())
                    <form action="{{ route('peminjaman.return', $borrowing->id_borrowing) }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-info" onclick="return confirm('Kembalikan buku ini?')">Kembalikan</button>
                    </form>
                @endif
                <a href="{{ route('peminjaman.edit', $borrowing->id_borrowing) }}" class="btn btn-success btn-sm">Edit</a>
                <form action="{{ route('peminjaman.destroy', $borrowing->id_borrowing) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" class="text-end fw-bold">Total</td>
            <td>Rp {{ number_format($borrowings->sum('fee'), 0, ',', '.') }}</td>
            <td class="text-danger fw-bold">Rp {{ number_format($borrowings->sum('penalty'), 0, ',', '.') }}</td>
            <td></td>
        </tr>
    </tfoot>

    </table>

    <div class="d-flex justify-content-end">
        {{ $borrowings->links() }}
    </div>
</div>
@endsection
