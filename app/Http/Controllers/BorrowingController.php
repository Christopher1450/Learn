<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Borrowing;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Borrower;
use App\Models\BookUnit;
use Illuminate\Support\Facades\Storage;


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
        'kode_unit' => $unit->kode_unit,
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
            'borrower_name' => $borrower->name,
            'borrower_id' => $borrower->id,
            'borrow_date' => now(),
            'return_date' => now()->addDays(7),
            'bukti_pengembalian' => '',
            'bukti_pembayaran' => '',
        ]);

        $buku->decrement('stock');
        if ($buku->stock <= 0) {
            $buku->status = 'unavailable';
            $buku->save();
        }

        return redirect()->route('peminjaman.index')->with('success', 'Buku berhasil dipinjam.');
    }
    public function uploadBukti(Request $request)
    {
        $request->validate([
            'id_borrowing' => 'required|exists:borrowings,id_borrowing',
            'bukti_pengembalian' => 'required|file|mimes:jpg,jpeg,png,webp,tiff|max:10240',
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,webp,tiff|max:10240',
        ]);

        $borrowing = Borrowing::with(['user', 'buku', 'unit'])->where('id_borrowing', $request->id_borrowing)->firstOrFail();

        if (auth()->id() !== $borrowing->user->id && !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak');
        }

        $dateNow = now()->format('Ymd_His');
        $borrowerName = str_replace(' ', '_', strtolower($borrowing->borrower_name));
        $userId = $borrowing->user->id;

        $file1 = $request->file('bukti_pengembalian');
        $file1Name = "{$borrowerName}-{$userId}-pengembalian-{$dateNow}.{$file1->getClientOriginalExtension()}";
        $path1 = $file1->storeAs('bukti_pengembalian', $file1Name, 'public');

        $file2 = $request->file('bukti_pembayaran');
        $file2Name = "{$borrowerName}-{$userId}-pembayaran-{$dateNow}.{$file2->getClientOriginalExtension()}";
        $path2 = $file2->storeAs('bukti_pembayaran', $file2Name, 'public');

        $borrowing->bukti_pengembalian = $path1;
        $borrowing->bukti_pembayaran = $path2;
        $borrowing->returned_at = now();

        $daysBorrowed = Carbon::parse($borrowing->borrow_date)->diffInDays(now());
        $borrowing->fee = $daysBorrowed * 10000;

        $lateDays = Carbon::parse($borrowing->return_date)->diffInDays(now(), false);
        $borrowing->penalty = $lateDays > 0 ? $lateDays * 5000 : 0;

        if ($borrowing->unit) {
            $borrowing->unit->status = 'available';
            $borrowing->unit->save();
        }

        $borrowing->buku->increment('stock');

        $borrowing->save();

        return redirect()->route('peminjaman.index')->with('success', 'Bukti berhasil diupload dan status peminjaman diperbarui.');
    }

    
    public function downloadBukti($type, $id)
    {
        $borrowing = Borrowing::where('id_borrowing', $id)->firstOrFail();

        $filePath = $type === 'pengembalian'
            ? $borrowing->bukti_pengembalian
            : $borrowing->bukti_pembayaran;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        $borrowerName = str_replace('_', ' ', ucfirst($borrowing->borrower_name));
        $typeReadable = $type === 'pengembalian' ? 'Bukti Pengembalian' : 'Bukti Pembayaran';
        $fileExt = pathinfo($filePath, PATHINFO_EXTENSION);
        $fileName = "{$typeReadable} {$borrowerName}.{$fileExt}";

        return response()->streamDownload(function () use ($filePath) {
            echo Storage::disk('public')->get($filePath);
        }, $fileName);
    }
           
//     public function downloadBukti($type, $id)
// {
//     $borrowing = Borrowing::where('id_borrowing', $id)->firstOrFail();

//     // Authorization: hanya user terkait atau admin yang boleh akses
//     // if (auth()->id() !== $borrowing->user_id && !auth()->user()->isAdmin()) {
//     //     abort(403, 'Kamu tidak punya akses ke file ini.');
//     // }

//     // Pilih file path sesuai jenis
//     $filePath = $type === 'pengembalian' ? $borrowing->bukti_pengembalian : $borrowing->bukti_pembayaran;

//     if (!$filePath || !Storage::disk('public')->exists($filePath)) {
//         abort(404, 'File tidak ditemukan.');
//     }

//     return response()->download(storage_path('app/public/' . $filePath));
// }
    
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

        if ($borrowing->unit) {
            $borrowing->unit->update(['status' => 'available']);
        }

        return redirect()->route('peminjaman.index')->with('success', 'Buku berhasil dikembalikan.');
    }

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
