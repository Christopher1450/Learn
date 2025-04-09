@extends('layouts.app')

<nav class="navbar navbar-dark bg-dark">
    <div class="container d-flex justify-content-between align-items-left">
        <a href="{{ url()->previous() }}" class="btn btn-light me-auto">🔙 Kembali</a>
    </div>
</nav>

@section('title', 'Buat Peminjaman Baru')

@section('content')
<div class="container mt-4">
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
            <label for="borrower_name" class="form-label">Nama Peminjam</label>
            <input list="borrower-list" type="text" id="borrower_name" name="borrower_name" class="form-control" placeholder="Ketik nama..." required>
            <datalist id="borrower-list">
                @foreach ($borrowers as $br)
                    <option value="{{ $br->name }}">
                @endforeach
            </datalist>
        </div>

        {{-- Otomatis isi tanggal lahir --}}
        <div class="mb-3">
            <label for="borrower_dob" class="form-label">Tanggal Lahir</label>
            <input type="date" id="borrower_dob" name="borrower_dob" class="form-control" readonly required>
        </div>

        {{-- Pilih Buku --}}
        <div class="mb-3">
            <label for="id_buku" class="form-label">Pilih Buku</label>
            <select name="id_buku" id="id_buku" class="form-control" required>
                <option value="">-- Pilih Buku --</option>
                @foreach ($buku as $book)
                    <option value="{{ $book->id_buku }}">{{ $book->judul_buku }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tgl_pinjam" class="form-label">Tanggal Pinjam</label>
            <input type="date" name="tgl_pinjam" id="tgl_pinjam" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
            <label for="tgl_kembali" class="form-label">Tanggal Kembali</label>
            <input type="date" name="tgl_kembali" id="tgl_kembali" class="form-control" value="{{ \Carbon\Carbon::now()->addDays(7)->format('Y-m-d') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

{{-- Embed data peminjam sebagai JSON --}}
<script type="application/json" id="borrowers-data">
    {!! $borrowers->toJson() !!}
</script>

{{-- Script Autocomplete --}}
<script>
    const borrowers = JSON.parse(document.getElementById('borrowers-data').textContent);

    document.getElementById('borrower_name').addEventListener('input', function () {
        const inputName = this.value.toLowerCase();
        const match = borrowers.find(br => br.name.toLowerCase() === inputName);
        document.getElementById('borrower_dob').value = match ? match.date_of_birth : '';
    });
</script>
@endsection
