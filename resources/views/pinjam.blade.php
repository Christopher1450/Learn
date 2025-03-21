<!-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Peminjaman Buku</title>
</head>
<body>
    <h2>Peminjaman Buku</h2>
    <form action="{{ url('/sirkulasi/pinjam') }}" method="POST">
        @csrf
        <label for="buku_id">ID Buku:</label>
        <input type="number" name="buku_id" required>
        <br>
        
        <label for="anggota_id">ID Anggota:</label>
        <input type="number" name="anggota_id" required>
        <br>
        
        <label for="tgl_pinjam">Tanggal Pinjam:</label>
        <input type="date" name="tgl_pinjam" required>
        <br>

        <button type="submit">Pinjam Buku</button>
    </form>
</body>
</html> -->
