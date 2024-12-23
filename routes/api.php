<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['api'])->group(function () {
    // Autentikasi API menggunakan token
    Route::post('/login', [LoginController::class, 'loginUser']);
    Route::post('/logout', [LoginController::class, 'logout']);

    Route::get('/register', [RegisterController::class, 'registerPage'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/verify-email/{token}', [RegisterController::class, 'verifyEmail'])->name('verify-email');
    Route::post('/resend-verification-email', [RegisterController::class, 'resendVerificationEmail'])->name('resend-verification-email');

    // Rute API terproteksi (harus terautentikasi)
    Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
        return $request->user();
    });
});
