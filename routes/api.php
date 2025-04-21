<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use Illuminate\Http\Request;
use App\Models\BookUnit;


// Route::apiResource('books', BookController::class);
Route::apiResource('categories', CategoryController::class);

Route::post('borrow/{book}', [BorrowController::class, 'borrow']);
Route::post('return/{book}', [BorrowController::class, 'return']);
Route::get('borrowed', [BorrowController::class, 'borrowedList']);
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth:sanctum');
Route::apiResource('buku', BukuController::class);
Route::apiResource('anggota', AnggotaController::class);

// Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
//     Route::get('/user', function (Request $request) {
//         return $request->user();
//     });
// });
Route::get('/cari-buku-by-kode/{kode_unit}', function ($kode_unit) {
    $unit = BookUnit::with('buku.categories')->where('kode_unit', $kode_unit)->first();
    if (!$unit) return response()->json(null);

    return response()->json([
        'id_buku' => $unit->buku->id_buku,
        'judul_buku' => $unit->buku->judul_buku,
        'kategori' => $unit->buku->categories->pluck('name')->implode(', '),
        'status' => $unit->status,
    ]);
});