<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\SirkulasiController;
use Illuminate\Support\Facades\Route;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowingController;

//Home
Route::get('/', function () {
    return view('welcome');
});

// Login
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
// Route::post('/login', [LoginController::class, 'loginProcess'])
//     ->name('login.process')
//     ->middleware('throttle:5,1'); // rate limit 5 kali : 1 menit
Route::post('/login', [LoginController::class, 'loginProcess'])->name('login.process');


// Dashboard
Route::get('/dashboard', function () {
    $buku = Buku::all();
    return view('dashboard', compact('buku'));
})->name('dashboard')->middleware('auth');
Route::resource('buku', BukuController::class)->middleware('auth');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

// Peminjaman 
// Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index')->middleware('auth');
// Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create')->middleware('auth');
// Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store')->middleware('auth');
// Route::resource('peminjaman', PeminjamanController::class)->middleware('auth');
// Route::post('/peminjaman/{id}/return', [PeminjamanController::class, 'returnBook'])->name('peminjaman.return')->middleware('auth');
    Route::resource('peminjaman', BorrowingController::class)->middleware('auth');
    Route::get('/peminjaman/create', [BorrowingController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman/borrow/{buku}', [BorrowingController::class, 'borrow'])->name('peminjaman.borrow');
    Route::post('/peminjaman/borro', [BorrowingController::class, 'borrow'])->name('peminjaman.borrow');


// // Mengelola Peminjaman Buku
// Route::get('/peminjaman/{id}/edit', [PeminjamanController::class, 'edit'])->name('peminjaman.edit')->middleware('auth');
// Route::put('/peminjaman/{id}', [PeminjamanController::class, 'update'])->name('peminjaman.update')->middleware('auth');
// Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy')->middleware('auth');

// Form Peminjaman Buku
Route::get('/pinjam', function () {
    return view('pinjam');
})->name('pinjam.form')->middleware('auth');
Route::post('/sirkulasi/pinjam', [SirkulasiController::class, 'pinjam'])->name('sirkulasi.pinjam')->middleware('auth');

Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create')->middleware('auth');
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
Route::post('/buku', [BukuController::class, 'store'])->name('buku.store')->middleware('auth');
Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->where('id', '[0-9a-fA-F-]+');
Route::put('/buku/{id}', [BukuController::class, 'update'])->name('buku.update');
