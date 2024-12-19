<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TantanganHarianController;
use App\Http\Controllers\ValidasiTantanganController;
use App\Http\Controllers\KontenEdukasiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute untuk Autentikasi
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Rute untuk Tantangan Harian
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tantangan-harian', [TantanganHarianController::class, 'index']); // Semua pengguna
    Route::get('/tantangan-harian/{id}', [TantanganHarianController::class, 'show']); // Detail tantangan
    Route::post('/tantangan-harian/{id}/mark-completed', [TantanganHarianController::class, 'markAsCompleted']);
    Route::get('/tantangan-harian/completed', [TantanganHarianController::class, 'getCompletedChallenges']);
    Route::get('/total-points', [TantanganHarianController::class, 'getTotalPoints']);

    // Rute khusus admin
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/tantangan-harian', [TantanganHarianController::class, 'store']);
        Route::put('/tantangan-harian/{id}', [TantanganHarianController::class, 'update']);
        Route::delete('/tantangan-harian/{id}', [TantanganHarianController::class, 'destroy']);
    });
});

/*
|--------------------------------------------------------------------------
| Rute untuk Validasi Tantangan oleh Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/validasi-tantangan/{id}', [ValidasiTantanganController::class, 'validateChallenge']);
    Route::get('/validasi-tantangan', [ValidasiTantanganController::class, 'index']);
    Route::post('/validasi-tantangan/{id}/reject', [ValidasiTantanganController::class, 'rejectChallenge']);
});

/*
|--------------------------------------------------------------------------
| Rute untuk Konten Edukasi
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/konten-edukasi', [KontenEdukasiController::class, 'index']); // Semua pengguna
    Route::get('/konten-edukasi/{kontenEdukasi}', [KontenEdukasiController::class, 'show']); // Detail konten edukasi
});

// Rute khusus admin
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/konten-edukasi', [KontenEdukasiController::class, 'store']);
    Route::put('/konten-edukasi/{kontenEdukasi}', [KontenEdukasiController::class, 'update']);
    Route::delete('/konten-edukasi/{kontenEdukasi}', [KontenEdukasiController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Rute untuk Manajemen Pengguna
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('users', UserController::class);
});
