<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Borrowing;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with(['buku', 'user'])->latest()->paginate(10);
        return view('borrowing.index', compact('borrowings'));
    }

    public function create()
    {
        $buku = Buku::where('stock', '>', 0)->get(); 
        return view('borrowing.create', compact('buku'));
    }

    public function borrow(Buku $buku, Request $request)
    {
        if ($buku->stock <= 0) {
            return back()->with('error', 'Stok buku habis');
        }

        Borrowing::create([
            'id' => auth()->id(),
            'id_buku' => $buku->id_buku,
            'borrow_date' => now(),
            'return_date' => now()->addDays(7),
        ]);

        $buku->decrement('stock');
        if ($buku->stock <= 0) {
            $buku->status = 'unavailable';
            $buku->save();
        }

        return redirect()->route('peminjaman.index')->with('success', 'Buku berhasil dipinjam');
    }

    public function return($id)
    {
        $borrowing = Borrowing::with('buku')->findOrFail($id);

        if ($borrowing->returned_at) {
            return redirect()->route('peminjaman.index')->with('info', 'Buku sudah dikembalikan sebelumnya.');
        }

        $borrowing->returned_at = now();

        $daysBorrowed = Carbon::parse($borrowing->borrow_date)->diffInDays(now());
        $feePerDay = 10000;
        $borrowing->fee = $daysBorrowed * $feePerDay;

        $lateDays = Carbon::parse($borrowing->return_date)->diffInDays(now(), false);
        $borrowing->penalty = $lateDays > 0 ? $lateDays * 5000 : 0;

        $borrowing->save();
        $borrowing->buku->increment('stock');

        return redirect()->route('peminjaman.index')->with('success', 'Buku berhasil dikembalikan.');
    }

    public function returnByBook(Buku $buku)
    {
        $borrow = Borrowing::where('id_buku', $buku->id_buku)
            ->where('id', auth()->id())
            ->whereNull('returned_at')
            ->first();

        if (!$borrow) {
            return response()->json(['message' => 'Tidak ada peminjaman aktif untuk buku ini'], 400);
        }

        $borrow->returned_at = now();

        $daysBorrowed = Carbon::parse($borrow->borrow_date)->diffInDays(now());
        $feePerDay = 10000;
        $borrow->fee = $daysBorrowed * $feePerDay;

        $lateDays = Carbon::parse($borrow->return_date)->diffInDays(now(), false);
        $borrow->penalty = $lateDays > 0 ? $lateDays * 5000 : 0;

        $borrow->save();
        $buku->increment('stock');

        return response()->json(['message' => 'Buku berhasil dikembalikan']);
    }

    public function borrowedingList()
    {
        $borrows = Borrowing::with('buku')
            ->where('id', auth()->id())
            ->whereNull('returned_at')
            ->get();

        return response()->json(['data' => $borrows]);
    }

    public function destroy($id)
    {
        $borrowing = Borrowing::findOrFail($id);

        if (!$borrowing->isReturned()) {
            $borrowing->buku->increment('stock');
        }

        $borrowing->delete();

        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
