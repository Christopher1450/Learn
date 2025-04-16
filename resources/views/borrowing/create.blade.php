@extends('layouts.app')
@section('content')

<div class="container mt-6">
<div class="row justify-content-center">
<div class="col-md-8 col-lg-6">
    <h2>Form Peminjaman Buku</h2>
    <!-- <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary mb-3">Lihat Daftar Peminjaman</a> -->

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('peminjaman.borrow', ['buku' => old('id_buku')]) }}" method="POST" id="borrow-form">
    @csrf

    <!-- <div class="form-group mb-3">
        <label for="borrower_select" class="form-label">Nama Peminjam:</label>
        <select id="borrower_select" class="form-select" required>
            <option value="">-- Pilih Nama --</option>
            @foreach ($borrowers as $br)
                <option value="{{ $br->name }}|{{ $br->date_of_birth }}">{{ $br->name }} ({{ $br->date_of_birth }})</option>
            @endforeach
            <option value="new">➕ Tambah Peminjam Baru</option>
        </select>
    </div> -->
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

    <div class="mb-3" id="input-jaminan-uang" style="display: none;">
        <label for="nilai_jaminan" class="form-label">Jumlah Uang Dijaminkan</label>
        <input type="text" class="form-control" id="nilai_jaminan" name="nilai_jaminan" placeholder="Rp 0,-">
    </div>

    <div class="mb-3" id="input-jaminan-barang" style="display: none;">
        <label for="bukti_jaminan" class="form-label">Upload Bukti Barang</label>
        <input type="file" class="form-control" id="bukti_jaminan" name="bukti_jaminan" accept="image/*">
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

    const borrowerSelect = document.getElementById('borrower_select');
    const newFields = document.getElementById('new-borrower-fields');
    const userName = document.getElementById('user_name');
    const userDob = document.getElementById('user_dob');

    borrowerSelect.addEventListener('change', function () {
        const value = this.value;
        if (value === "new") {
            newFields.style.display = "block";
            userName.value = "";
            userDob.value = "";
        } else {
            newFields.style.display = "none";
            const [name, dob] = value.split('|');
            userName.value = name;
            userDob.value = dob;
        }
    });

    form.addEventListener('submit', function () {
        if (borrowerSelect.value === "new") {
            userName.value = document.getElementById('borrower_name').value;
            userDob.value = document.getElementById('borrower_dob').value;
        }
    });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const jenis = document.getElementById('jenis_jaminan');
    const uangInput = document.getElementById('input-jaminan-uang');
    const barangInput = document.getElementById('input-jaminan-barang');

    jenis.addEventListener('change', function () {
        if (this.value === 'uang') {
            uangInput.style.display = 'block';
            barangInput.style.display = 'none';
        } else if (this.value === 'barang') {
            uangInput.style.display = 'none';
            barangInput.style.display = 'block';
        } else {
            uangInput.style.display = 'none';
            barangInput.style.display = 'none';
        }
    });

    // Format input uang
    document.getElementById('nilai_jaminan').addEventListener('input', function () {
        let val = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '');
        this.value = val ? 'Rp ' + parseInt(val).toLocaleString('id-ID') + ',-' : 'Rp 0,-';
    });
});

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

        const filtered = borrowers.filter(b =>
            b.name.toLowerCase().includes(keyword)
        );

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

@endsection
