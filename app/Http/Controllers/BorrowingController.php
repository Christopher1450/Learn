<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Borrowing;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Borrower;
use App\Models\BookUnit;

class BorrowingController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with(['buku', 'user', 'borrower', 'unit'])->latest()->paginate(100);
        return view('borrowing.index', compact('borrowings'));
    }

    public function create()
    {
        $buku = Buku::where('stock', '>', 0)->get();
        $borrowers = Borrower::all();
        return view('borrowing.create', compact('buku','borrowers'));
    }

    public function borrow(Buku $buku, Request $request)
{
    if ($buku->stock <= 0) {
        return back()->with('error', 'Stok buku habis');
    }

    $unit = $buku->units()->where('status', 'available')->first();
    if (!$unit) {
        return back()->with('error', 'Tidak ada unit buku yang tersedia.');
    }

    $borrower = Borrower::firstOrCreate([
        'name' => $request->user_name,
        'date_of_birth' => $request->user_dob
    ]);

    // Buat peminjaman
    Borrowing::create([
        'id' => auth()->id(),
        'id_buku' => $buku->id_buku,
        'kode_unit' => $unit->kode_unit, // mengikuti table
        'borrower_name' => $borrower->name,
        'borrower_id' => $borrower->id,
        'borrow_date' => now(),
        'return_date' => now()->addDays(7),
    ]);

    $buku->decrement('stock');
    $unit->status = 'unavailable';
    $unit->save();

    if ($buku->stock <= 0) {
        $buku->status = 'unavailable';
        $buku->save();
    }

    return redirect()->route('peminjaman.index')->with('success', 'Buku berhasil dipinjam.');
}

    public function store(Request $request)
    {
        $request->validate([
            'borrower_name' => 'required|string|max:255',
            'borrower_dob' => 'required|date',
            'id_buku' => 'required|exists:buku,id_buku',
        ]);

        $buku = Buku::findOrFail($request->id_buku);

        if ($buku->stock <= 0) {
            return back()->with('error', 'Stok buku habis');
        }

        $borrower = Borrower::firstOrCreate([
            'name' => $request->borrower_name,
            'date_of_birth' => $request->borrower_dob,
        ]);

        $unit = $buku->units()->where('status', 'available')->first();
        if (!$unit) {
            return back()->with('error', 'Tidak ada unit buku yang tersedia.');
        }

        $unit->update(['status' => 'borrowed']);

        Borrowing::create([
            'id' => auth()->id(),
            'id_buku' => $buku->id_buku,
            'kode_unit' => $unit->kode_unit,
            'borrower_id' => $borrower->id,
            'borrower_name' => $borrower->name,
            'borrow_date' => now(),
            'return_date' => now()->addDays(7),
        ]);

        $buku->decrement('stock');
        if ($buku->stock <= 0) {
            $buku->status = 'unavailable';
            $buku->save();
        }

        return redirect()->route('peminjaman.index')->with('success', 'Buku berhasil dipinjam.');
    }

    public function return($id)
    {
        $borrowing = Borrowing::with('buku', 'unit')->findOrFail($id);

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

        // Kembalikan status unit
        if ($borrowing->unit) {
            $borrowing->unit->update(['status' => 'available']);
        }

        return redirect()->route('peminjaman.index')->with('success', 'Buku berhasil dikembalikan.');
    }

    // BorrowingController.php

public function edit($id)
{
    $borrowing = Borrowing::with(['buku', 'borrower', 'unit'])->findOrFail($id);
    return view('borrowing.edit', compact('borrowing'));
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'borrow_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:borrow_date',
        ]);

        $borrowing = Borrowing::findOrFail($id);
        $borrowing->update([
            'borrow_date' => $request->borrow_date,
            'return_date' => $request->return_date,
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $borrowing = Borrowing::findOrFail($id);

        if (!$borrowing->isReturned()) {
            $borrowing->buku->increment('stock');
            if ($borrowing->unit) {
                $borrowing->unit->update(['status' => 'available']);
            }
        }

        $borrowing->delete();

        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
