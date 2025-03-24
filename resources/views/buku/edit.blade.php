@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Buku</h2>

    <form action="{{ route('buku.update', $buku->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="judul_buku" class="form-label">Judul Buku</label>
            <input type="text" name="judul_buku" id="judul_buku" class="form-control" value="{{ $buku->judul_buku }}" required>
        </div>

        <div class="mb-3">
            <label for="pengarang" class="form-label">Pengarang</label>
            <input type="text" name="pengarang" id="pengarang" class="form-control" value="{{ $buku->pengarang }}" required>
        </div>

        <div class="mb-3">
            <label for="penerbit" class="form-label">Penerbit</label>
            <input type="text" name="penerbit" id="penerbit" class="form-control" value="{{ $buku->penerbit }}" required>
        </div>

        <div class="mb-3">
            <label for="th_terbit" class="form-label">Tahun Terbit</label>
                <input 
                type="text" 
                name="th_terbit" 
                id="th_terbit" 
                class="form-control" 
                maxlength="4"
                pattern="\d{4}" 
                title="Masukkan 4 digit tahun" 
                required>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" name="stock" id="stock" class="form-control" value="{{ $buku->stock }}" min="1" required>
        </div>

        <!-- Kategori -->
        <div class="mb-3">
            <label for="category_id" class="form-label">Kategori</label>
            <div class="border p-3 rounded bg-theme">
                <div class="row">
                    @foreach ($categories as $category)
                        <div class="col-md-3">
                            <label class="d-flex align-items-center">
                                <input type="checkbox" name="category_id[]" value="{{ $category->id }}" class="form-check-input"
                                    {{ $buku->categories->contains($category->id) ? 'checked' : '' }}>
                                <span>{{ $category->name }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
