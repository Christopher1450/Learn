@extends('layouts.app')

@section('content')
<div class="container" style="margin-bottom: 25px;margin-top: 0px ;max-height: 800px;">
    <h3>Detail Buku</h3>
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-2">
                <div class="card-body">
                    <p><strong>Judul:</strong> {{ $buku->judul_buku }}</p>
                    <p><strong>Kode Buku:</strong> {{ $buku->id_buku }}</p>
                    <p><strong>Pengarang:</strong> {{ $buku->pengarang }}</p>
                    <p><strong>Penerbit:</strong> {{ $buku->penerbit }}</p>
                    <p><strong>Tahun Terbit:</strong> {{ $buku->th_terbit }}</p>
                    <p><strong>Stok:</strong> {{ $buku->stock }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($buku->status) }}</p>
                    <p><strong>Kategori:</strong> {{ $buku->categories->pluck('name')->implode(', ') }}</p>
                    <form action="{{ route('buku.addStock', $buku->id_buku) }}" method="POST">
                        @csrf
                        <div class="d-flex align-items-center gap-3">
                            <label for="jumlah">Add Stock:</label>
                            <input type="number" name="jumlah" id="jumlah" min="1" required class="form-control w-auto">
                            <button type="submit" class="btn btn-success btn-sm">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Buku</th>
                                <th>Nama Peminjam</th>
                                <th>Kode Unit</th>
                                <th>Barcode</th>
                                <th>QR Code</th>
                                <th>Tanggal Pinjam</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($units as $unit)
                            <tr>
                                <td>{{ $buku->judul_buku }}</td>
                                <td>{{ $unit->borrowing->borrower_name ?? '-' }}</td>
                                <td>{{ $unit->kode_unit }}</td>
                                <td>
                                    @if ($unit->barcode_path)
                                        <img src="{{ asset('storage/' . $unit->barcode_path) }}" width="50" alt="Barcode {{ $unit->kode_unit }}">
                                    @else
                                        <span class="text-muted">Tidak tersedia</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($unit->qr_path)
                                    <img src="{{ asset('storage/' . $unit->qr_path) }}" width="50" alt="QR {{ $unit->kode_unit }}">
                                    @else
                                        <span class="text-muted">Tidak tersedia</span>
                                    @endif
                                </td>

                                <td>{{ $unit->borrowing->borrow_date ?? '-' }}</td>
                                <td>
                                    @if ($unit->borrowing && !$unit->borrowing->returned_at)
                                        <span class="badge bg-warning text-dark">Belum Dikembalikan</span>
                                    @elseif ($unit->borrowing && $unit->borrowing->returned_at)
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @elseif (!$unit->borrowing && $unit->status === 'unavailable')
                                        <span class="badge bg-danger">Hilang (Data Dihapus)</span>
                                    @else
                                        <span class="badge bg-secondary">Belum Pernah Dipinjam</span>
                                    @endif
                                </td>
                                <td>
                                @if (!$unit->borrowing && $unit->status === 'unavailable')
                                <form action="{{ route('unit.destroy', $unit->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus unit ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                                @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
