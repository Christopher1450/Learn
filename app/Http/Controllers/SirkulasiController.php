<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sirkulasi;
use App\Models\Buku;
use App\Models\Anggota;

class SirkulasiController extends Controller
{
    // Pinjem
    public function pinjam(Request $request)
    {
        $validated = $request->validate([
            'id_buku' => 'required|exists:id,buku',
            'anggota_id' => 'required|exists:anggotas,id',
            'tgl_pinjam' => 'required|date',
        ]);

        $sirkulasi = Sirkulasi::create([
            'id_buku' => $validated['id_buku'],
            'anggota_id' => $validated['anggota_id'],
            'tgl_pinjam' => $validated['tgl_pinjam'],
            'status' => 'PIN',
        ]);

        return response()->json(['message' => 'Buku berhasil dipinjam', 'data' => $sirkulasi], 201);
    }

    public function kembali($id)
    {
        $sirkulasi = Sirkulasi::where('id', $id)->where('status', 'PIN')->first();
        if (!$sirkulasi) return response()->json(['message' => 'Data peminjaman tidak ditemukan'], 404);

        $sirkulasi->update([
            'tgl_kembali' => now(),
            'status' => 'KEM',
        ]);

        return response()->json(['message' => 'Buku berhasil dikembalikan', 'data' => $sirkulasi], 200);
    }

    // Daftar Buku yang Dipinjam
    public function listPeminjaman()
    {
        return response()->json(Sirkulasi::where('status', 'PIN')->with('buku', 'anggota')->get(), 200);
    }
}
