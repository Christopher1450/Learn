<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use Illuminate\Http\Request;

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