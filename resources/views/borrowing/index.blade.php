@php
    use Carbon\Carbon;
@endphp
<style>
    .btn-sm {
        padding-top: 4px !important;
        padding-bottom: 4px !important;
    }
    table td, table th {
        padding: 8px !important;
        vertical-align: middle;
    }
</style>
@extends('layouts.app')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@section('content')
<div class="container-fluid" style="margin-top: 10px; padding-left: 0 ;transform: scale(0.9); transform-origin: top left; width: max-content;">
    <h2 class="mb-3">List Peminjaman Buku</h2>
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
                <th>Jenis Jaminan</th>
                <th>Jumlah Jaminan</th>
                <th>Bukti Dikembalikan</th>
                <th>Bukti Bayar</th>
                <th>Pengembalian_jaminan</th>
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
                @endif
            </td>
            <td>
                @if ($borrowing->isReturned())
                    <span class="badge bg-success">Dikembalikan</span>
                @else
                    <span class="badge bg-warning text-dark">Belum Dikembalikan</span>
                @endif
            </td>
            <td>{{ $borrowing->jenis_jaminan }}</td>
            <!-- <td>{{ $borrowing->jumlah_jaminan }}</td> -->
            <td>
                @if ($borrowing->jenis_jaminan == 'uang')
                    Rp {{ number_format($borrowing->jumlah_jaminan, 0, ',', '.') }}
                @else
                    {{ $borrowing->nilai_jaminan }}
                @endif
            </td>
            <td class="align-top">
                @if ($borrowing->bukti_pengembalian)
                <a href="{{ route('bukti.download', ['type' => 'pengembalian', 'id' => $borrowing->id_borrowing]) }}" target="_blank" class="btn">
                    <img src="{{ asset('images/Edited_Download_Logo.jpg') }}" alt="Download" width="60" height="60">
                </a>
                    @else
                    -
                @endif
            </td>

            <td class="align-top">
                @if ($borrowing->bukti_pembayaran)
                    <a href="{{ route('bukti.download', ['type' => 'pembayaran', 'id' => $borrowing->id_borrowing]) }}" target="_blank" class="btn">
                        <img src="{{ asset('images/Edited_Download_Logo.jpg') }}" alt="Download" width="60" height="60">
                    <!-- class="btn btn-info btn-sm">⬇️ Bukti Pembayaran</a> -->
                @else
                    -
                @endif
            </td>
            <td class="align-top">
                @if ($borrowing->returned_at)
                    @if ($borrowing->jenis_jaminan === 'uang')
                        <div class="text-success fw-bold">
                            Uang kembalian:<br>
                            Rp {{ number_format($borrowing->pengembalian_jaminan ?? 0, 0, ',', '.') }}
                        </div>
                    @elseif ($borrowing->jenis_jaminan === 'barang' && $borrowing->bukti_jaminan)
                        <a href="{{ asset('storage/' . $borrowing->bukti_jaminan) }}" target="_blank" class="btn btn-info btn-sm">
                            Pengembalian barang Bukti
                        </a>
                    @endif
                @else
                    <span>-</span>
                @endif
            </td>

            <td>
                <div class="d-flex align-items-center gap-2">
                    @if (!$borrowing->isReturned())
                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#returnModal" data-id="{{ $borrowing->id_borrowing }}">Kembalikan</button>
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
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>
</div>
</div>

<!-- Modal Upload Bukti -->
<div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('peminjaman.upload') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="id_borrowing" id="modal_borrowing_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Upload Bukti</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="bukti_pengembalian" class="form-label">Bukti Pengembalian</label>
            <input type="file" name="bukti_pengembalian" class="form-control" accept=".raw,.jpg,.jpeg,.png,.webp,.tiff" required>
          </div>
          <div class="mb-3">
            <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran</label>
            <input type="file" name="bukti_pembayaran" class="form-control" accept=".raw,.jpg,.jpeg,.png,.webp,.tiff" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Upload</button>
        </div>
      </div>
    </form>
  </div>
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

        const returnModal = document.getElementById('returnModal');
        returnModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            document.getElementById('modal_borrowing_id').value = id;
        });
    });
</script>
@endpush
