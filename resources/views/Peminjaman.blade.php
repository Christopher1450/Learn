<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    // yg lg pinjem 
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Peminjaman::with('buku')->where('id', Auth::id())->get();
        }

        $peminjaman = Peminjaman::with('buku', 'user')->paginate(100);
        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $buku = Buku::where('stock', '>', 0)->get();
        return view('peminjaman.create', compact('buku'));
    }

    // Store peminjaman
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date|after:tanggal_pinjam'
        ]);

        Peminjaman::create([
            'id' => Auth::id(),
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil ditambahkan.');
    }

    // Return book 
    public function returnBook($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'tanggal_kembali' => now()
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Buku berhasil dikembalikan.');
    }

        public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dihapus.');
    }
}
