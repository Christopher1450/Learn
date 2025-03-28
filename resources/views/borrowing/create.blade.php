@extends('layouts.app')
@section('content')

<div class="container mt-4">
    <h2>Form Peminjaman Buku</h2>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary mb-3">Lihat Daftar Peminjaman</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('peminjaman.borrow', ['buku' => old('id_buku')]) }}" method="POST" id="borrow-form">
        @csrf
        <div class="form-group mb-3">
            <label for="id_buku" class="form-label">Pilih Buku:</label>
            <select name="id_buku" id="id_buku" class="form-select" required>
                <option value="">-- Pilih Buku --</option>
                @foreach ($buku as $b)
                <option 
                    value="{{ $b->id_buku }}" 
                    data-judul="{{ $b->judul_buku }}" 
                    data-kategori="{{ $b->categories->pluck('name')->implode(', ') }}" 
                    data-stok="{{ $b->stock }}" 
                    data-status="{{ $b->stock > 0 ? 'Available' : 'Not Available' }}">
                    {{ $b->judul_buku }} - {{ $b->categories->pluck('name')->implode(', ') ?: 'Tanpa Kategori' }} (Stok: {{ $b->stock }})
                </option>
                @endforeach
            </select>
        </div>

        <div id="buku-info" class="card shadow-sm p-3 mb-3 bg-light" style="display: none;">
            <h5 class="card-title mb-3">Informasi Buku</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Judul:</strong> <span id="info-judul"></span></p>
                    <p><strong>Kategori:</strong> <span id="info-kategori"></span></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status:</strong> <span id="info-status" class="badge"></span></p>
                    <p><strong>Stok:</strong> <span id="info-stok"></span></p>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Pinjam</button>
    </form>
</div>

<script>
    const select = document.getElementById('id_buku');
    const form = document.getElementById('borrow-form');

    select.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const bukuId = selected.value;

        if (bukuId) {
            form.action = '/peminjaman/borrow/' + bukuId;
            document.getElementById('info-judul').innerText = selected.dataset.judul;
            document.getElementById('info-kategori').innerText = selected.dataset.kategori;
            document.getElementById('info-stok').innerText = selected.dataset.stok;

            const statusEl = document.getElementById('info-status');
            const status = selected.dataset.status.trim().toLowerCase();
            statusEl.innerText = status.charAt(0).toUpperCase() + status.slice(1);
            statusEl.className = 'badge ' + (status === 'available' ? 'bg-success' : 'bg-danger');
            document.getElementById('buku-info').style.display = 'block';
        } else {
            document.getElementById('buku-info').style.display = 'none';
        }
    });
</script>

@endsection
