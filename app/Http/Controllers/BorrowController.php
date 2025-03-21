<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku; // Sesuai dengan tabel "buku"
use App\Models\Borrowing; // Sesuai dengan tabel "borrowings"

class BorrowController extends Controller
{
    public function borrow(Buku $buku){
        if ($buku->stock <= 0) {
            return response()->json(['message' => 'Stok buku habis'], 400);
        }

        $existingBorrow = Borrowing::where('id', auth()->id())
            ->where('buku_id', $buku->id_buku)
            ->whereNull('returned_at')
            ->exists();

        if ($existingBorrow) {
            return response()->json(['message' => 'Anda sudah meminjam buku ini dan belum mengembalikannya'], 400);
        }

        $borrow = Borrowing::create([
            'id' => auth()->id(),
            'id_buku' => $buku->id_buku,
            'borrowed_at' => now()
        ]);

        $buku->decrement('stock');

        return response()->json(['message' => 'Buku berhasil dipinjam', 'data' => $borrow]);
    }

    // buat balikan buku
    public function return(Buku $buku){
        // Cek apakah user belum dikembalikan
        $borrow = Borrowing::where('buku_id', $buku->id_buku)
            ->where('id', auth()->id())
            ->whereNull('returned_at')
            ->first();

        if (!$borrow) {
            return response()->json(['message' => 'Tidak ada peminjaman buku yang perlu dikembalikan'], 400);
        }
        $borrow->update(['returned_at' => now()]);

        // Tambahkan stok kembali
        $buku->increment('stock');

        return response()->json(['message' => 'Buku berhasil dikembalikan']);
    }

    // untuk mendapatkan daftar buku yang lgi dipinjam
    public function borrowedList(){
        $borrows = Borrowing::with('buku')
            ->where('id', auth()->id()) 
            ->whereNull('returned_at')
            ->get();

        return response()->json(['data' => $borrows]);
    }
}
