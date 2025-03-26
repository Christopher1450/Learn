<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Borrowing;
use App\Models\Buku;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Menampilkan statistik perpustakaan dalam format JSON.
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $totalBuku = Buku::count();
        $totalCategories = Category::count();
        $totalBorrowed = Borrowing::whereNull('returned_at')->count();

        $query = Buku::with('categories');

        if ($request->has('category_id') && $request->category_id != '') {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        $buku = $query->get();

        return view('dashboard', compact('buku', 'categories', 'totalBuku', 'totalCategories', 'totalBorrowed'));
    }
}