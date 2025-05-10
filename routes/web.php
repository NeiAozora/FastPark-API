<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('mahasiswa-lintas', MahasiswaLintasFakultasController::class);
Route::apiResource('petugas', PetugasController::class);
Route::apiResource('parkir', ParkirController::class);


Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);
});