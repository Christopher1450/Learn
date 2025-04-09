<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel Perpustakaan')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/Login.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('scripts')
  <!-- <style>
    /* Navbar container */
.navbar {
  overflow: hidden;
  background-color: #333;
  font-family: Arial;
}

/* Links inside the navbar */
.navbar a {
  float: left;
  font-size: 16px;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

/* The dropdown container */
.dropdown {
  float: left;
  overflow: hidden;
}

/* Dropdown button */
.dropdown .dropbtn {
  font-size: 16px;
  border: none;
  outline: none;
  color: white;
  padding: 14px 16px;
  background-color: inherit;
  font-family: inherit; /* Important for vertical align on mobile phones */
  margin: 0; /* Important for vertical align on mobile phones */
}

/* Add a red background color to navbar links on hover */
.navbar a:hover, .dropdown:hover .dropbtn {
  background-color: red;
}

/* Dropdown content (hidden by default) */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
  float: none;
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
  text-align: left;
}

/* Add a grey background color to dropdown links on hover */
.dropdown-content a:hover {
  background-color: #ddd;
}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {
  display: block;
}
  </style> -->
</head>
<!-- pop Tambah Peminjam -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('users.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Peminjam</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="birth_date" class="form-label">Tanggal Lahir</label>
            <input type="date" name="birth_date" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<body>
    <!-- nav -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">Laravel Perpustakaan</a>

<!-- shortcut -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-3">
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Home</a></li>
                    <!-- <div class="dropdown">
                      <button class="dropbtn">Dropdown v
                        <i class="fa fa-caret-down"></i>
                      </button>
                      </div>
                    <div class="dropdown-content">
                    <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#addUserModal">Tambah Peminjam</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('peminjaman.create') }}">Buat Peminjaman</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('peminjaman.store') }}">Daftar Pinjam</a></li>
                    </div> -->
                    <li class="nav-item"><a class="nav-link" href="{{ route('peminjaman.create') }}">Buat Peminjaman</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('buku.create') }}">Tambah Buku</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('peminjaman.store') }}">Daftar Pinjam</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#addUserModal">Tambah Peminjam</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('category.index') }}">Kategori</a></li>
                </ul>
              </div> 
        <div>
            {{-- Logout --}}
            <div class="d-flex ms-auto">
                <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="addUserForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addUserModalLabel">Tambah Peminjam Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="form-group mb-3">
            <label for="new_name">Nama:</label>
            <input type="text" id="new_name" name="name" class="form-control" required>
          </div>
          <div class="form-group mb-3">
            <label for="new_birth_date">Tanggal Lahir:</label>
            <input type="date" id="new_birth_date" name="birth_date" class="form-control" required>
          </div>
          <div id="addUserSuccess" class="alert alert-success d-none"></div>
          <div id="addUserError" class="alert alert-danger d-none"></div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

    <div class="container mt-5 pt-5">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <div id="loading-overlay" style="display: none;
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255, 255, 255, 0.7); z-index: 9999;
        justify-content: center; align-items: center;
        font-size: 1.5rem;">
        <div class="spinner-border text-primary" role="status"></div>
        <span class="ms-3">Loading...</span>
    </div>

        <!-- Modal Tambah User
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="birth_date" class="form-label">Tanggal Lahir</label>
                    <input type="date" name="birth_date" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </div>
        </form>
    </div>
    </div> -->


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const overlay = document.getElementById("loading-overlay");

            document.querySelectorAll("form").forEach(form => {
                form.addEventListener("submit", function () {
                    overlay.style.display = "flex";
                });
            });

            document.querySelectorAll(".show-loading").forEach(btn => {
                btn.addEventListener("click", function () {
                    overlay.style.display = "flex";
                });
            });
        });
    </script>

<!-- ajax -->

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('addUserForm');
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const name = document.getElementById('new_name').value;
        const birthDate = document.getElementById('new_birth_date').value;
        const successEl = document.getElementById('addUserSuccess');
        const errorEl = document.getElementById('addUserError');

        fetch("{{ route('users.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ name, birth_date: birthDate })
        })
        .then(res => res.json())
        .then(data => {
            successEl.textContent = 'Peminjam berhasil ditambahkan!';
            successEl.classList.remove('d-none');
            errorEl.classList.add('d-none');
            form.reset();

            setTimeout(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
                modal.hide();
                location.reload(); // Reload biar muncul di autocomplete
            }, 1200);
        })
        .catch(() => {
            errorEl.textContent = 'Gagal menambahkan peminjam.';
            errorEl.classList.remove('d-none');
        });
    });
});
</script>

    @stack('scripts')
</body>
</html>
