@extends('layouts.app')
@section('content')

<div class="container mt-6">
<div class="row justify-content-center">
<div class="col-md-8 col-lg-6">
    <h2>Form Peminjaman Buku</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="#" method="POST" id="borrow-form" enctype="multipart/form-data">
    @csrf

    <div class="form-group mb-3">
    <label for="borrower_name_search" class="form-label">Nama Peminjam:</label>
    <input type="text" id="borrower_name_search" class="form-control" placeholder="Ketik nama..." autocomplete="off">
    <div id="borrower-suggestion" class="list-group mt-1" style="display: none; position: absolute; z-index: 10;"></div>
    </div>

    <div id="new-borrower-fields" style="display: none;">
        <div class="form-group mb-3">
            <label for="borrower_name" class="form-label">Nama Baru:</label>
            <input type="text" name="borrower_name" id="borrower_name" class="form-control" placeholder="Nama lengkap">
        </div>
        <div class="form-group mb-3">
            <label for="borrower_dob" class="form-label">Tanggal Lahir:</label>
            <input type="date" name="borrower_dob" id="borrower_dob" class="form-control">
        </div>
    </div>

    <input type="hidden" name="user_name" id="user_name">
    <input type="hidden" name="user_dob" id="user_dob">

    <div class="mb-3">
        <label class="form-label">Jenis Jaminan</label>
        <select id="jenis_jaminan" name="jenis_jaminan" class="form-select" required>
            <option value="">-- Pilih --</option>
            <option value="uang">Uang</option>
            <option value="barang">Barang</option>
        </select>
    </div>

    <div class="mb-3" id="input-jaminan-uang">
        <label for="nilai_jaminan" class="form-label">Jumlah Jaminan (Rp)</label>
        <input type="text" name="nilai_jaminan" id="nilai_jaminan" class="form-control" required>
        <small id="info-jaminan" class="form-text text-muted">Nilai minimum jaminan akan muncul di sini...</small>
    </div>

    <div class="mb-3" id="input_barang" style="display: none;">
        <label for="bukti_jaminan" class="form-label">Upload Bukti Barang</label>
        <input type="file" name="bukti_jaminan" id="bukti_jaminan" class="form-control">
    </div>

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
document.getElementById('jenis_jaminan').addEventListener('change', function () {
    const jenis = this.value;
    document.getElementById('input-jaminan-uang').style.display = jenis === 'uang' ? 'block' : 'none';
    document.getElementById('input_barang').style.display = jenis === 'barang' ? 'block' : 'none';
});

document.getElementById('nilai_jaminan').addEventListener('input', function () {
    let val = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '');
    this.value = val ? 'Rp ' + parseInt(val).toLocaleString('id-ID') + ',-' : 'Rp 0,-';
});

document.addEventListener("DOMContentLoaded", function () {
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
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const borrowers = @json($borrowers);
    const input = document.getElementById('borrower_name_search');
    const suggestionBox = document.getElementById('borrower-suggestion');
    const userName = document.getElementById('user_name');
    const userDob = document.getElementById('user_dob');

    input.addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        suggestionBox.innerHTML = '';

        if (!keyword) {
            suggestionBox.style.display = 'none';
            return;
        }

        const filtered = borrowers.filter(b => b.name.toLowerCase().includes(keyword));

        if (filtered.length === 0) {
            suggestionBox.style.display = 'none';
            return;
        }

        filtered.forEach(b => {
            const item = document.createElement('button');
            item.classList.add('list-group-item', 'list-group-item-action');
            item.textContent = `${b.name} (${b.date_of_birth})`;
            item.addEventListener('click', function () {
                input.value = b.name;
                userName.value = b.name;
                userDob.value = b.date_of_birth;
                suggestionBox.style.display = 'none';
            });
            suggestionBox.appendChild(item);
        });

        suggestionBox.style.display = 'block';
    });

    document.addEventListener('click', function (e) {
        if (!suggestionBox.contains(e.target) && e.target !== input) {
            suggestionBox.style.display = 'none';
        }
    });
});
</script>

<script>
const bukuSelect = document.getElementById('id_buku');
const infoJaminan = document.getElementById('info-jaminan');

const bukuData = @json($buku->mapWithKeys(function($b) {
    return [$b->id_buku => ['judul' => $b->judul_buku, 'jaminan' => $b->nilai_minimum_jaminan]];
}));

bukuSelect.addEventListener('change', function () {
    const selected = this.value;
    if (bukuData[selected]) {
        infoJaminan.textContent = 'Nilai minimum jaminan: Rp ' + parseInt(bukuData[selected].jaminan).toLocaleString('id-ID');
    } else {
        infoJaminan.textContent = 'Nilai minimum jaminan akan muncul di sini...';
    }
});
</script>
@endsection
