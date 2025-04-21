@extends('layouts.app')
@section('content')

<div class="container mt-6" style="margin-top: 400px; margin-bottom: 100px;">
<div class="row justify-content-center">
<div class="col-md-8 col-lg-6">
    <h2>Form Peminjaman Buku</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('peminjaman.store') }}" method="POST" id="borrow-form" enctype="multipart/form-data">

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

    <!-- <div class="mb-3" id="input-jaminan-uang">
        <label for="nilai_jaminan" class="form-label">Jumlah Jaminan (Rp)</label>
        <input type="text" name="nilai_jaminan" id="nilai_jaminan" class="form-control" required>
        <small id="info-jaminan" class="form-text text-muted">Nilai minimum jaminan akan muncul di sini...</small>
    </div> -->

    <div class="mb-3">
        <label for="kode_unit" class="form-label">Kode Unit Buku:</label>
        <input type="text" id="kode_unit" name="kode_unit" class="form-control" placeholder="Scan atau ketik kode unit" autocomplete="off">
        <input type="hidden" id="id_buku" name="id_buku">
    </div>


    <!-- <div class="mb-3">
        <label for="kode_unit" class="form-label">Kode Unit Buku:</label>
        <div class="input-group">
            <input type="text" id="kode_unit" name="kode_unit" class="form-control" placeholder="Scan atau ketik kode unit">
            <button type="button" class="btn btn-outline-secondary" onclick="startScanner()">📷 Scan</button> -->
        <!-- </div>
    </div> -->
    <!-- <div class="mb-3">
        <label for="kode_unit" class="form-label">Kode Unit (Scan/Manual)</label>
        <input type="text" name="kode_unit" id="kode_unit" class="form-control" placeholder="Scan barcode buku" required>
    </div>

    <div id="barcode-scanner" style="width: 100%; max-width: 400px; display: none;"></div> -->

    <!-- <div class="text-center my-2">
        <button type="button" class="btn btn-outline-success" onclick="startQRScanner()">📷 Scan QR</button>
    </div> -->
    <!-- scanner QR -->
    <div class="d-flex justify-content-center gap-5 align-items-center my-3 flex-wrap">

    <div class="d-flex align-items-center gap-2">
        <label class="form-label fw-semibold mb-0">Scan QR</label>
        <label class="switch">
            <input type="checkbox" id="toggle-qr" onchange="toggleScannerSwitch('qr')">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="d-flex align-items-center gap-2">
        <label class="form-label fw-semibold mb-0">Scan Barcode</label>
        <label class="switch">
            <input type="checkbox" id="toggle-barcode" onchange="toggleScannerSwitch('barcode')">
            <span class="slider round"></span>
        </label>
    </div>

</div>

<div id="scanner-box" style="width: 100%; max-width: 400px; margin: auto; display: none;"></div>



    <!-- <div id="scanner-box" style="width: 100%; max-width: 400px; margin: auto; display: none;"></div> -->


    <!-- <div class="text-center my-2">
        <button type="button" class="btn btn-outline-secondary" onclick="startScanner()">📷 Scan Barcode</button>
    </div>
    <div id="scanner" style="width: 100%; max-width: 400px; margin: auto; display: none;"></div> -->

    <div id="book-info" class="card shadow-sm p-3 mb-3 bg-light" style="display: none;">
    <h5 class="card-title mb-3">Informasi Buku</h5>
    <div class="row">
        <div class="col-md-6">
            <p><strong>Judul:</strong> <span id="book-judul"></span></p>
            <p><strong>Kategori:</strong> <span id="book-kategori"></span></p>
        </div>
        <div class="col-md-6">
            <p><strong>Status:</strong> <span id="book-status" class="badge"></span></p>
        </div>
    </div>
</div>


    <button type="submit" class="btn btn-primary">Pinjam</button>
</form>
</div>

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
document.getElementById('jenis_jaminan').addEventListener('change', function () {
    const jenis = this.value;
    const nilaiJaminan = document.getElementById('nilai_jaminan');
    const buktiBarang = document.getElementById('bukti_jaminan');

    document.getElementById('input-jaminan-uang').style.display = jenis === 'uang' ? 'block' : 'none';
    document.getElementById('input_barang').style.display = jenis === 'barang' ? 'block' : 'none';

    nilaiJaminan.required = jenis === 'uang';
    buktiBarang.required = jenis === 'barang';
});


document.getElementById('nilai_jaminan').addEventListener('input', function () {
    let val = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '');
    this.value = val ? 'Rp ' + parseInt(val).toLocaleString('id-ID') + ',-' : 'Rp 0,-';
});


document.getElementById('kode_unit').addEventListener('input', debounce(function () {
    const kode = this.value.trim();
    if (!kode) return;

    fetch(`/api/cari-buku-by-kode/${kode}`)
        .then(res => res.json())
        .then(data => {
            if (data && data.judul_buku) {
                document.getElementById('book-info').style.display = 'block';
                document.getElementById('book-judul').innerText = data.judul_buku;
                document.getElementById('book-kategori').innerText = data.kategori;
                document.getElementById('book-status').innerText = data.status;
                document.getElementById('book-status').className = 'badge ' + (data.status === 'available' ? 'bg-success' : 'bg-danger');
                document.getElementById('id_buku').value = data.id_buku;
            } else {
                document.getElementById('book-info').style.display = 'none';
                document.getElementById('id_buku').value = '';
            }
        });
}, 500));

// debounce supaya nggak spam request saat ngetik cepat
function debounce(func, delay) {
    let timer;
    return function () {
        clearTimeout(timer);
        timer = setTimeout(func.bind(this), delay);
    };
}
</script>

<!-- <script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let scannerActive = false;
    let html5QrcodeScanner;

    function startScanner() {
        const scannerDiv = document.getElementById('scanner');

        if (scannerActive) {
            html5QrcodeScanner.stop().then(() => {
                scannerActive = false;
                scannerDiv.style.display = 'none';
            });
            return;
        }

        html5QrcodeScanner = new Html5Qrcode("scanner");
        scannerDiv.style.display = 'block';
        scannerActive = true;

        html5QrcodeScanner.start(
            { facingMode: "environment" },
            {
                fps: 10,
                qrbox: { width: 250, height: 100 }
            },
            (decodedText, decodedResult) => {
                document.getElementById('kode_unit').value = decodedText;
                document.getElementById('kode_unit').dispatchEvent(new Event('blur'));

                html5QrcodeScanner.stop().then(() => {
                    scannerActive = false;
                    scannerDiv.style.display = 'none';
                });
            },
            (error) => {
                // bisa diabaikan: error saat tidak berhasil scan
            }
        );
    }
</script> -->
<!-- <script src="https://unpkg.com/html5-qrcode"></script>

<script>
    let qrScannerActive = false;
    let qrScanner;

    function startQRScanner() {
        const qrReader = document.getElementById('qr-reader');

        if (qrScannerActive) {
            qrScanner.stop().then(() => {
                qrScannerActive = false;
                qrReader.style.display = 'none';
            });
            return;
        }

        qrReader.style.display = 'block';
        qrScanner = new Html5Qrcode("qr-reader");
        qrScanner.start(
            { facingMode: "environment" },
            {
                fps: 10,
                qrbox: 250
            },
            (decodedText, decodedResult) => {
                document.getElementById('kode_unit').value = decodedText;
                document.getElementById('kode_unit').dispatchEvent(new Event('blur'));

                qrScanner.stop().then(() => {
                    qrReader.style.display = 'none';
                    qrScannerActive = false;
                });
            },
            (error) => {
                // ignore scan error
            }
        ).then(() => {
            qrScannerActive = true;
        });
    }
</script> -->

<!-- <script src="https://unpkg.com/html5-qrcode"></script>
    <div id="reader" width="600px"></div> -->
    <!-- <input type="text" id="kode_unit" name="kode_unit" class="form-control" placeholder="Scan atau ketik kode unit" required> -->

<!-- <script>
    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById('kode_unit').value = decodedText;
        html5QrcodeScanner.clear();
    }

    const html5QrcodeScanner = new Html5QrcodeScanner("reader", {
        fps: 10,
        qrbox: 250
    });
    html5QrcodeScanner.render(onScanSuccess);
</script> -->

<!-- <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<div id="reader" style="width: 300px"></div>
<input type="text" id="kode_unit" name="kode_unit" class="form-control" placeholder="Scan QR untuk mengisi otomatis"> -->

<!-- <script>
    const input = document.getElementById('kode_unit');

    function onScanSuccess(decodedText) {
        input.value = decodedText;
        document.getElementById('reader').innerHTML = ''; // Stop scanner
    }

    const html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start({ facingMode: "environment" }, { fps: 10, qrbox: 250 }, onScanSuccess);
</script>

<script>
document.getElementById('kode_unit').addEventListener('blur', function () {
    const kode = this.value;
    fetch(`/api/book-unit/${kode}`)
        .then(res => res.json())
        .then(data => {
            // misal tampilkan info judul, status dll
            document.getElementById('info-judul').innerText = data.judul;
        });
});
</script> -->
<!-- <script src="https://unpkg.com/html5-qrcode"></script>

<script>
    let scannerActive = false;
    let html5QrcodeScanner;

    function startScanner() {
        const scannerDiv = document.getElementById('barcode-scanner'); // pastikan ID sesuai

        if (scannerActive) {
            html5QrcodeScanner.stop().then(() => {
                scannerActive = false;
                scannerDiv.style.display = 'none';
            });
            return;
        }

        html5QrcodeScanner = new Html5Qrcode("barcode-scanner");
        scannerDiv.style.display = 'block';
        scannerActive = true;

        html5QrcodeScanner.start(
            { facingMode: "environment" },
            {
                fps: 10,
                qrbox: { width: 250, height: 100 }
            },
            (decodedText, decodedResult) => {
                document.getElementById('kode_unit').value = decodedText;
                document.getElementById('kode_unit').dispatchEvent(new Event('blur'));

                html5QrcodeScanner.stop().then(() => {
                    scannerActive = false;
                    scannerDiv.style.display = 'none';
                });
            },
            (error) => {
                // bisa diabaikan
            }
        );
    }
</script> -->

<style>
.switch {
  position: relative;
  display: inline-block;
  width: 55px;
  height: 30px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  background-color: #ccc;
  border-radius: 30px;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  transition: .4s;
}

.slider::before {
  position: absolute;
  content: "";
  height: 22px;
  width: 22px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  border-radius: 50%;
  transition: .4s;
}

input:checked + .slider {
  background-color: #4CAF50;
}

input:checked + .slider::before {
  transform: translateX(24px);
}

.slider.round {
  border-radius: 30px;
}
</style>




<script src="https://unpkg.com/html5-qrcode"></script>

<script>
let activeScanner = null;
let scannerInstance = null;

function toggleScannerSwitch(mode) {
    const qrToggle = document.getElementById('toggle-qr');
    const barcodeToggle = document.getElementById('toggle-barcode');
    const scannerDiv = document.getElementById('scanner-box');

    const isQR = mode === 'qr';
    const isOn = isQR ? qrToggle.checked : barcodeToggle.checked;

    // Matikan scanner jika toggle yang sama diklik ulang
    if (activeScanner === mode && isOn === false) {
        scannerInstance?.stop().then(() => {
            scannerDiv.style.display = 'none';
            activeScanner = null;
        });
        return;
    }

    // Kalau scanner lain aktif, matikan dulu
    if (activeScanner && scannerInstance) {
        scannerInstance.stop().then(() => {
            qrToggle.checked = false;
            barcodeToggle.checked = false;
            startScanner(mode);
        });
    } else {
        startScanner(mode);
    }
}

function startScanner(mode) {
    const scannerDiv = document.getElementById('scanner-box');
    const qrToggle = document.getElementById('toggle-qr');
    const barcodeToggle = document.getElementById('toggle-barcode');

    scannerDiv.innerHTML = '';
    scannerDiv.style.display = 'block';

    const qrboxSize = mode === 'qr' ? 250 : { width: 300, height: 100 };

    scannerInstance = new Html5Qrcode("scanner-box");
    scannerInstance.start(
    { facingMode: "environment" },
    { fps: 10, qrbox: qrboxSize },
    (decodedText) => {
        document.getElementById('kode_unit').value = decodedText;

        // Gunakan event 'input' agar trigger pencarian info buku
        document.getElementById('kode_unit').dispatchEvent(new Event('input'));

        scannerInstance.stop().then(() => {
            scannerDiv.style.display = 'none';
            activeScanner = null;
            qrToggle.checked = false;
            barcodeToggle.checked = false;
        });
    },
    (error) => {
    }
    ).then(() => {
        activeScanner = mode;
        // Toggle hanya yang aktif
        qrToggle.checked = mode === 'qr';
        barcodeToggle.checked = mode === 'barcode';
    });
}
</script>

<script>
document.getElementById('borrower_name_search').addEventListener('input', function () {
    const keyword = this.value.trim();
    const suggestionBox = document.getElementById('borrower-suggestion');

    if (keyword.length === 0) {
        suggestionBox.style.display = 'none';
        suggestionBox.innerHTML = '';
        return;
    }

    fetch(`/api/cari-peminjam?keyword=${encodeURIComponent(keyword)}`)
        .then(res => res.json())
        .then(data => {
            suggestionBox.innerHTML = '';

            if (data.length > 0) {
                data.forEach(item => {
                    const el = document.createElement('a');
                    el.href = '#';
                    el.className = 'list-group-item list-group-item-action';
                    el.textContent = `${item.name} (${item.date_of_birth})`;

                    el.addEventListener('click', function (e) {
                        e.preventDefault();

                        document.getElementById('borrower_name_search').value = item.name;
                        document.getElementById('user_name').value = item.name;
                        document.getElementById('user_dob').value = item.date_of_birth;

                        suggestionBox.style.display = 'none';
                        suggestionBox.innerHTML = '';
                        document.getElementById('new-borrower-fields').style.display = 'none';
                    });

                    suggestionBox.appendChild(el);
                });

                suggestionBox.style.display = 'block';
                document.getElementById('new-borrower-fields').style.display = 'none';
            } else {
                suggestionBox.innerHTML = '<div class="list-group-item text-muted">Tidak ditemukan. Masukkan sebagai peminjam baru.</div>';
                suggestionBox.style.display = 'block';
                document.getElementById('new-borrower-fields').style.display = 'block';
                document.getElementById('borrower_name').value = keyword;
            }
        });
});
</script>

<!-- //males ganti nama takut hancur  -->
<script>
    document.getElementById('borrow-form').addEventListener('submit', function () {
        const userName = document.getElementById('user_name').value;
        const userDob = document.getElementById('user_dob').value;

        document.getElementById('borrower_name').value = userName;
        document.getElementById('borrower_dob').value = userDob;
    });
</script>

@endsection