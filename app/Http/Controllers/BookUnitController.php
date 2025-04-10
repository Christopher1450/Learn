<?php
namespace App\Http\Controllers;

use App\Models\BookUnit;
use Illuminate\Http\Request;

class BookUnitController extends Controller
{
        public function recover($id)
    {
        $unit = BookUnit::findOrFail($id);

        // Pulihkan hanya kalau statusnya 'lost' atau 'unavailable' TANPA borrowing aktif
        $hasBorrowing = \App\Models\Borrowing::where('kode_unit', $unit->kode_unit)->whereNull('returned_at')->exists();

        if (($unit->status === 'lost' || $unit->status === 'unavailable') && !$hasBorrowing) {
            $unit->update(['status' => 'available']);
            return back()->with('success', 'Unit berhasil dipulihkan.');
        }

        return back()->with('error', 'Unit tidak bisa dipulihkan karena masih ada peminjaman aktif.');
    }
        public function destroy($id)
        {
            $unit = \App\Models\BookUnit::findOrFail($id);

            // Optional: hanya bisa dihapus kalau tidak punya borrowing
            if ($unit->borrowing) {
                return back()->with('error', 'Unit tidak bisa dihapus karena sedang dipinjam.');
            }

            $unit->delete();

            return back()->with('success', 'Unit berhasil dihapus permanen.');
    }
}
