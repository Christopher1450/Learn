@extends('layouts.app')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="{{ asset('js/delete.js') }}" defer></script>
</head>
<body>


    <!-- Navbar -->
    <!-- <nav class="navbar navbar-dark">
        <a class="navbar-brand" href="#">Laravel Perpustakaan</a>
        <a href="{{ route('logout') }}" class="btn btn-danger logout-btn">Logout</a>
    </nav> -->

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h2 class="mb-3">Selamat datang, {{ Auth::user()->name }}</h2>

        <div class="row">
            <div class="col-md-4">
                <div class="bg-primary text-white stats-card">
                    <h5>Total Buku</h5>
                    <h2>{{ $totalBuku }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-success text-white stats-card">
                    <h5>Total Kategori</h5>
                    <h2>{{ $totalCategories }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-warning text-white stats-card">
                    <h5>Buku Dipinjam</h5>
                    <h2>{{ $totalBorrowed }}</h2>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Daftar Buku</h3>
            <div class="d-flex align-items-center gap-2">
                <!-- Filter -->
                <form method="GET" action="{{ route('dashboard') }}" class="d-inline">
                    @csrf
                    <select name="category_id" class="form-select d-inline w-auto" onchange="this.form.submit()">
                        <option value="" {{ request('category_id') == '' ? 'selected' : '' }}>All</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <!-- Pinjam Buku -->
                <!-- <a href="{{ route('peminjaman.create') }}" class="btn btn-warning">Pinjam Buku</a> -->

                <!-- Tambah Buku (admin oly) -->
                <!-- @if(Auth::check() && strtolower(Auth::user()->role) === 'admin')
                    <a href="{{ route('buku.create') }}" class="btn btn-primary">Tambah Buku</a>
                @endif -->
            </div>
        </div>


        <div class="table-responsive">
        <table class="table table-bordered w-100 shadow-sm">            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th>Kategori</th>
                    <th>Stock</th>
                    <th>Status</th>
                    @if(Auth::user()->role === 'admin')
                        <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($buku as $book)
                <tr>
                    <td>{{ $book->judul_buku }}</td>
                    <td>
                        @if($book->categories->isNotEmpty()) 
                            {{ $book->categories->pluck('name')->join(', ') }}
                        @else
                            Tidak Ada Kategori
                        @endif
                    </td>
                    <td>{{ $book->stock }}</td>
                    <td>
                        <span class="badge {{ $book->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                            {{ $book->stock > 0 ? 'Available' : 'Not Available' }}
                        </span>
                    </td>
                    @if(Auth::user()->role === 'admin')
                        <td>
                            <a href="{{ route('buku.edit', $book->id_buku) }}" class="btn btn-warning">Edit</a>
                            <!-- <a href="{{ route('peminjaman.create', $book->id_buku) }}" class="btn btn-primary btn-sm">Pinjam</a> -->
                            <button type="button" class="btn btn-danger"
                            data-bs-toggle="modal" 
                            data-bs-target="#confirmDeleteModal"
                            data-url="{{ route('buku.destroy', $book->id_buku) }}">
                            Delete
                        </button>
                        <a href="{{ route('buku.detail', $book->id_buku) }}" class="btn btn-info btn-sm">Detail</a>
                        </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus buku ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script>
        function setDeleteAction(action) {
            document.getElementById('deleteForm').setAttribute('action', action);
        }

        fetch("{{ route('dashboard.stats') }}")
            .then(response => response.json())
            .then(data => {
                document.getElementById("total_buku").textContent = data.total_buku;
                document.getElementById("total_categories").textContent = data.total_categories;
                document.getElementById("total_borrowed").textContent = data.total_borrowed;
            });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
