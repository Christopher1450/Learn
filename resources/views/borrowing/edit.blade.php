@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 fw-bold text-center">Edit Data Peminjaman</h2>

    <div class="card shadow p-4" style="max-width: 800px; margin: auto;">
        <form action="{{ route('peminjaman.update', $borrowing->id_borrowing) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="borrower_name" class="form-label">Nama Peminjam</label>
                    <input type="text" name="borrower_name" id="borrower_name" class="form-control"
    value="{{ old('borrower_name', $borrowing->borrower->name ?? $borrowing->borrower_name) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="borrow_date" class="form-label">Tanggal Pinjam</label>
                    <input type="date" name="borrow_date" id="borrow_date" class="form-control" value="{{ old('borrow_date', \Carbon\Carbon::parse($borrowing->borrow_date)->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="return_date" class="form-label">Tanggal Kembali</label>
                    <input type="date" name="return_date" id="return_date" class="form-control" value="{{ old('return_date', \Carbon\Carbon::parse($borrowing->return_date)->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="fee" class="form-label">Fee</label>
                    <input type="number" name="fee" id="fee" class="form-control" value="{{ old('fee', $borrowing->fee) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="penalty" class="form-label" style="color: #DC143C;">Denda</label>
                    <input type="number" name="penalty" id="penalty" class="form-control" value="{{ old('penalty', $borrowing->penalty) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="returned_at" class="form-label">Tanggal Pengembalian</label>
                    <input type="date" name="returned_at" id="returned_at" class="form-control"
                           value="{{ old('returned_at', $borrowing->returned_at ? \Carbon\Carbon::parse($borrowing->returned_at)->format('Y-m-d') : '') }}">
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
