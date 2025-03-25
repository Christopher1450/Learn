@extends('layouts.app')
<div class="container" style="padding-top: 300px; padding-bottom: 10px;">

@section('content')

    <h2 class="mb-4 fw-bold text-center">Edit Buku</h2>
    <div class="container mt-5" style="max-width: 800px;">
    <form action="{{ route('buku.update', $buku->id_buku) }}" method="POST">
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
                value="{{ $buku->th_terbit }}"
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
                        <div class="col-md-3 mb-2">
                            <label class="form-check-label d-flex align-items-center" style="font-size: 0.9rem;">
                                <input type="checkbox" name="category_id[]" value="{{ $category->id }}" class="form-check-input me-2"
                                    {{ $buku->categories->contains($category->id) ? 'checked' : '' }}>
                                {{ $category->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


        <button type="submit" class="btn btn-success" >Simpan Perubahan</button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>

        <ul>DOne</ul>
    </form>
</div>
@endsection

        <!-- estetika -->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const tahunInput = document.getElementById("th_terbit");

                tahunInput.addEventListener("input", function () {
                    let value = tahunInput.value;

                    // Hapus karakter yg bkn int
                    value = value.replace(/\D/g, "");

                    
                    if (value.length > 4) {
                        value = value.slice(-4);
                    }

                    tahunInput.value = value;
                });
            });
        </script>