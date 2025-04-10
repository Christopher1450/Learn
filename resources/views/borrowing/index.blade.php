@php
    use Carbon\Carbon;
@endphp
<style>
    .btn-sm {
        padding-top: 4px !important;
        padding-bottom: 4px !important;
    }
</style>
@extends('layouts.app')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@section('content')
<div class="container" style="margin-top: 250px;"><div class="container" style="margin-bottom: 25px;">


    <h2 class="mb-3">List Peminjaman Buku</h2>

    <!-- <a href="{{ route('peminjaman.create') }}" class="btn btn-primary mb-3">Buat Peminjaman Baru</a> -->

    <table class="table table-bordered shadow-sm">
        <thead>
            <tr>
                <th>Buku</th>
                <th>Nama Pengakses</th>
                <th>Kode Unit</th>
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
            <td>{{ $borrowing->unit->kode_unit ?? '-' }}</td>
            <td>{{ $borrowing->borrower_name }}</td>
            <td>{{ $borrowing->borrow_date ? \Carbon\Carbon::parse($borrowing->borrow_date)->format('Y-m-d') : '-' }}</td>
            <td>{{ $borrowing->returned_at ? \Carbon\Carbon::parse($borrowing->returned_at)->format('Y-m-d') : '-' }}</td>
            <!-- <td>{{ $borrowing->borrower_name ?? 'N/A' }}</td>
            <td>{{ $borrowing->borrower_dob ?? 'N/A' }}</td> -->

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
            <td>
            <div class="d-flex align-items-center gap-2">
                @if (!$borrowing->isReturned())
                    <form action="{{ route('peminjaman.return', $borrowing->id_borrowing) }}" method="POST" class="m-0">
                        @csrf
                        <button class="btn btn-sm btn-info" onclick="return confirm('Kembalikan buku ini?')">Kembalikan</button>
                    </form>
                @endif

                <a href="{{ route('peminjaman.edit', $borrowing->id_borrowing) }}" class="btn btn-success btn-sm">Edit</a>

                <form action="{{ route('peminjaman.destroy', $borrowing->id_borrowing) }}" method="POST" class="m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Delete</button>
                </form>
            </div>
        </td>

        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7" class="text-end fw-bold">Total</td>
            <td>Rp {{ number_format($borrowings->sum('fee'), 0, ',', '.') }}</td>
            <td class="text-danger fw-bold">Rp {{ number_format($borrowings->sum('penalty'), 0, ',', '.') }}</td>
            <td></td>
        </tr>
    </tfoot>

    </table>

    <!-- <div class="d-flex justify-content-end">
        {{ $borrowings->links() }}
    </div> -->
</div>
@endsection
@push('scripts')
<!-- jQuery + DataTables JS buat layout -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        $('.table').DataTable({
            paging: true,
            ordering: true,
            info: true,
            destroy: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                infoEmpty: "Menampilkan 0 hingga 0 dari 0 entri",
                infoFiltered: "(disaring dari _MAX_ total entri)",
                zeroRecords: "Tidak ada entri yang ditemukan",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    });
</script>
@endpush
