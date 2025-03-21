@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">List Peminjaman Buku</h2>

    <a href="{{ route('peminjaman.create') }}" class="btn btn-primary mb-3">Buat Peminjaman Baru</a>

    <table class="table table-bordered shadow-sm">
        <thead>
            <tr>
                <th>Buku</th>
                <th>Nama Mahasiswa/Siswa</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Durasi Peminjaman</th>
                <th>Denda</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($peminjaman as $pinjam)
            <tr>
                <td>{{ $pinjam->buku->judul_buku }}</td>
                <td>{{ $pinjam->user->name }}</td>
                <td>{{ $pinjam->tgl_pinjam }}</td>
                <td>{{ $pinjam->tgl_kembali }}</td>
                <td>
                    @php
                        $durasi = \Carbon\Carbon::parse($pinjam->tgl_kembali)->diffInDays(\Carbon\Carbon::parse($pinjam->tgl_pinjam));
                        $terlambat = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($pinjam->tgl_kembali), false);
                    @endphp
                    @if ($terlambat < 0)
                        <span class="text-danger">Durasi Habis / {{ abs($terlambat) }} Hari</span>
                    @else
                        {{ $durasi }} Hari
                    @endif
                </td>
                <td>{{ number_format($pinjam->denda, 0, ',', '.') }}</td>
                <td class="d-flex gap-2">
                    <a href="{{ route('peminjaman.edit', $pinjam->id) }}" class="btn btn-success btn-sm">Edit</a>
                    <form action="{{ route('peminjaman.destroy', $pinjam->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-end">
        {{ $peminjaman->links() }}
    </div>
</div>
@endsection
