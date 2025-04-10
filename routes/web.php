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
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Borrower;
use App\Http\Controllers\CategoryController;
use App\Models\BookUnit;
use App\Http\Controllers\BookUnitController;
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
    Route::post('/peminjaman/borrow', [BorrowingController::class, 'borrow'])->name('peminjaman.borrow');
    Route::post('/peminjaman/return/{borrowing}', [BorrowingController::class, 'return'])->name('peminjaman.return');
    Route::delete('/peminjaman/{id}', [BorrowingController::class, 'destroy'])->name('peminjaman.destroy');
Route::get('/peminjaman/{id}/edit', [BorrowingController::class, 'edit'])->name('peminjaman.edit');
Route::put('/peminjaman/{id}', [BorrowingController::class, 'update'])->name('peminjaman.update');



// // Mengelola Peminjaman Buku
// Route::get('/peminjaman/{id}/edit', [PeminjamanController::class, 'edit'])->name('peminjaman.edit')->middleware('auth');
// Route::put('/peminjaman/{id}', [PeminjamanController::class, 'update'])->name('peminjaman.update')->middleware('auth');
// Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy')->middleware('auth');

// Form Peminjaman Buku
Route::get('/pinjam', function () {
    return view('pinjam');
})->name('pinjam.form')->middleware('auth');
Route::post('/sirkulasi/pinjam', [SirkulasiController::class, 'pinjam'])->name('sirkulasi.pinjam')->middleware('auth');

// Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create')->middleware('auth');
// Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
// Route::post('/buku', [BukuController::class, 'store'])->name('buku.store')->middleware('auth');
// Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->where('id', '[0-9a-fA-F-]+');
// Route::put('/buku/{id}', [BukuController::class, 'update'])->name('buku.update');
Route::resource('buku', BukuController::class)->middleware('auth');
Route::get('/buku/{id}/detail', [BukuController::class, 'show'])->name('buku.detail');
Route::post('/buku/{id}/add-stock', [\App\Http\Controllers\BukuController::class, 'addStock'])->name('buku.addStock');
Route::post('/unit/{id}/recover', [\App\Http\Controllers\BookUnitController::class, 'recover'])->name('unit.recover');
Route::delete('/unit/{id}', [BookUnitController::class, 'destroy'])->name('unit.destroy');



Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::post('/users/store', [UserController::class, 'store'])->name('users.store');

Route::post('/users/store', function (Request $request) {
    $borrower = Borrower::create([
        'name' => $request->name,
        'date_of_birth' => $request->birth_date,
    ]);

    return response()->json($borrower); // bisa diganti redirect atau return view jika kamu mau
})->name('users.store');

Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');
Route::get('/category/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
Route::put('/category/{category}', [CategoryController::class, 'update'])->name('category.update');
