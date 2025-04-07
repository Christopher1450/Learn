<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\Buku;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create()
    {
        $borrowers = Borrower::all();
        $buku = Buku::with('categories')->get();

        return view('borrowing.create', compact('borrowers', 'buku'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
        ]);

        $user = Borrower::create([
            'name' => $request->name,
            'date_of_birth' => $request->dob,
        ]);

        return redirect()->route('dashboard')->with('success', 'Peminjam berhasil ditambahkan!');
    }
}
