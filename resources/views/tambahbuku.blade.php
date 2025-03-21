<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Tambah Buku Baru</h2>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('buku.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="judul_buku" class="form-label">Judul Buku</label>
                <input type="text" class="form-control" id="judul_buku" name="judul_buku" required>
            </div>
            <div class="mb-3">
                <label for="pengarang" class="form-label">Pengarang</label>
                <input type="text" class="form-control" id="pengarang" name="pengarang" required>
            </div>
            <div class="mb-3">
                <label for="penerbit" class="form-label">Penerbit</label>
                <input type="text" class="form-control" id="penerbit" name="penerbit" required>
            </div>
            <div class="mb-3">
                <label for="th_terbit" class="form-label">Tahun Terbit</label>
                <input type="number" class="form-control" id="th_terbit" name="th_terbit" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">Pilih Kategori</option>
                    <!-- <option value="1" {{ request('category_id') == 1 ? 'selected' : '' }}>Fiction</option>
                <option value="2" {{ request('category_id') == 2 ? 'selected' : '' }}>Non-Fiction</option>
                <option value="3" {{ request('category_id') == 3 ? 'selected' : '' }}>Science</option>
                <option value="4" {{ request('category_id') == 4 ? 'selected' : '' }}>History</option>
                <option value="5" {{ request('category_id') == 5 ? 'selected' : '' }}>Biography</option>
                <option value="6" {{ request('category_id') == 6 ? 'selected' : '' }}>Fantasy</option>
                <option value="7" {{ request('category_id') == 7 ? 'selected' : '' }}>Mystery</option>
                <option value="8" {{ request('category_id') == 8 ? 'selected' : '' }}>Romance</option>
                <option value="9" {{ request('category_id') == 9 ? 'selected' : '' }}>Thriller</option>
                <option value="10" {{ request('category_id') == 10 ? 'selected' : '' }}>Horror</option>
                <option value="11" {{ request('category_id') == 11 ? 'selected' : '' }}>Self-Help</option>
                <option value="12" {{ request('category_id') == 12 ? 'selected' : '' }}>Health</option>
                <option value="13" {{ request('category_id') == 13 ? 'selected' : '' }}>Travel</option>
                <option value="14" {{ request('category_id') == 14 ? 'selected' : '' }}>Children's</option>
                <option value="15" {{ request('category_id') == 15 ? 'selected' : '' }}>Cooking</option> -->
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html>
