<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookUnit;
use App\Models\Buku;

class BookSearchController extends Controller
{
    public function findByKode($kode)
    {
        $unit = BookUnit::with('buku.categories')->where('kode_unit', $kode)->first();

        if (!$unit || !$unit->buku) {
            return response()->json(['error' => 'Buku tidak ditemukan.'], 404);
        }

        return response()->json([
            'id_buku' => $unit->buku->id_buku,
            'judul_buku' => $unit->buku->judul_buku,
            'kategori' => $unit->buku->categories->pluck('name')->implode(', '),
            'status' => $unit->status,
            'kode_unit' => $unit->kode_unit,
        ]);
    }
}
