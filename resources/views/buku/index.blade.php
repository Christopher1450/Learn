<table>
    <tr>
        <th>Judul Buku</th>
        <th>Pengarang</th>
        <th>Penerbit</th>
        <th>Kategori</th>
        <th>Aksi</th>
    </tr>
    @foreach($buku as $item)
    <tr>
        <td>{{ $item->judul_buku }}</td>
        <td>{{ $item->pengarang }}</td>
        <td>{{ $item->penerbit }}</td>
        <td>{{ $item->category->nama_kategori ?? 'Tanpa Kategori' }}</td>
        <td>
            <a href="{{ route('buku.edit', $item->id_buku) }}">Edit</a>
            <form action="{{ route('buku.destroy', $item->id_buku) }}" method="POST" style="display:inline;">
                @csrf 
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

</table>
