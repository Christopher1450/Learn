<!-- @extends('layouts.app')
@section('content')

<div class="container mt-4">
    <h2 class="mb-4">Tambah User Baru</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" name="name" id="name" class="form-control" required placeholder="Nama user...">
        </div>

        <div class="mb-3">
            <label for="birth_date" class="form-label">Tanggal Lahir</label>
            <input type="date" name="birth_date" id="birth_date" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

@endsection -->
