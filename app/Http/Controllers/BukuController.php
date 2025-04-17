<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Buku, Category, BookUnit};
use Illuminate\Support\Str;
use Milon\Barcode\DNS1D;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('category')->get();
        return view('buku.index', compact('buku'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('buku.create', compact('categories'));
    }

    public function show($id)
    {
        $buku = Buku::with(['categories', 'units'])->findOrFail($id);
        $units = $buku->units()->with(['borrowing' => function ($q) {
            $q->latest();
        }])->get();

        return view('buku.detail', compact('buku','units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_buku' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'th_terbit' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'category_id' => 'required|array',
            'category_id.*' => 'exists:categories,id',
            'stock' => 'required|integer|min:1',
            'nilai_jaminan' => 'required|string|max:255',
        ], [
            'category_id.required' => 'Pilih setidaknya satu kategori.',
            'category_id.*.exists' => 'Kategori yang dipilih tidak valid.',
        ]);

        $judul = $request->judul_buku;
        $prefix = strtoupper(substr($judul, 0, 1));
        $jumlahPrefix = Buku::where('kode_unit', 'LIKE', $prefix . '%')->count() + 1;
        $kodeBuku = $prefix . str_pad($jumlahPrefix, 5, '0', STR_PAD_LEFT);

        $rawJaminan = $request->nilai_jaminan;
        $cleanJaminan = preg_replace('/[^\d]/', '', $rawJaminan);

        $buku = Buku::create([
            'kode_unit' => $kodeBuku,
            'judul_buku' => $judul,
            'pengarang' => $request->pengarang,
            'penerbit' => $request->penerbit,
            'th_terbit' => $request->th_terbit,
            'stock' => $request->stock,
            'nilai_jaminan' => (int) $cleanJaminan,
            'status' => 'available'
        ]);

        $barcode = new DNS1D();
        $barcode->setStorPath(public_path('barcodes'));

        for ($i = 0; $i < $request->stock; $i++) {
            $kodeUnit = Str::upper(Str::random(8));
            $barcodeImage = $barcode->getBarcodePNGPath($kodeUnit, 'C39');

            BookUnit::create([
                'id_buku' => $buku->id_buku,
                'kode_unit' => $kodeUnit,
                'status' => 'available',
                'barcode_path' => 'barcodes/' . basename($barcodeImage)
            ]);
        }

        $buku->categories()->sync($request->category_id);

        return redirect()->route('dashboard')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function addStock(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        $buku = Buku::findOrFail($id);
        $jumlah = $request->jumlah;

        $barcode = new DNS1D();
        $barcode->setStorPath(public_path('barcodes'));

        for ($i = 0; $i < $jumlah; $i++) {
            $kodeUnit = Str::upper(Str::random(8));
            $barcodeImage = $barcode->getBarcodePNGPath($kodeUnit, 'C39');

            BookUnit::create([
                'id_buku' => $buku->id_buku,
                'kode_unit' => $kodeUnit,
                'status' => 'available',
                'barcode_path' => 'barcodes/' . basename($barcodeImage)
            ]);
        }

        $buku->increment('stock', $jumlah);

        if ($buku->status === 'unavailable') {
            $buku->status = 'available';
            $buku->save();
        }

        return back()->with('success', 'Stock Added!');
    }

    public function edit($id)
    {
        $buku = Buku::with('categories')->findOrFail($id);
        $categories = Category::all();
        return view('buku.edit', compact('buku', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_buku' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'th_terbit' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'category_id' => 'required|array',
            'category_id.*' => 'exists:categories,id',
            'stock' => 'required|integer|min:1',
            'nilai_jaminan' => 'required|string|max:255',
        ]);

        $buku = Buku::findOrFail($id);

        $buku->update([
            'judul_buku' => $request->judul_buku,
            'pengarang' => $request->pengarang,
            'penerbit' => $request->penerbit,
            'th_terbit' => $request->th_terbit,
            'stock' => $request->stock,
            'nilai_jaminan' => preg_replace('/[^\d]/', '', $request->nilai_jaminan),
        ]);

        $buku->categories()->sync($request->category_id);

        return redirect()->route('dashboard')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Buku::findOrFail($id)->delete();
        return redirect()->route('dashboard')->with('success', 'Buku berhasil dihapus.');
    }
}
