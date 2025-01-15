<?php

use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegisterController;

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
    Route::get('/logout', [LoginController::class, 'logout']);

    Route::get('/register', [RegisterController::class, 'registerPage'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/verify-email/{token}', [RegisterController::class, 'verifyEmail'])->name('verify-email');
    Route::post('/resend-verification-email', [RegisterController::class, 'resendVerificationEmail'])->name('resend-verification-email');

    // // Rute API terproteksi (harus terautentikasi)
    // Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    //     return $request->user();
    // });
//member
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/member/profile', [MemberController::class, 'profile'])->name('profile');
        Route::post('/member/profileUpdate', [MemberController::class, 'profileUpdate'])->name('profileUpdate');
        Route::post('/member/members', [MemberController::class, 'members'])->name('members');
        Route::get('/member/member/{id}', [MemberController::class, 'member'])->name('member');
        Route::get('/member/showDocument', [DocumentController::class, 'showDocument'])->name('showDocument');
        Route::post('/member/uploadDocument', [DocumentController::class, 'uploadDocument'])->name('uploadDocument');
        Route::post('/member/uploadHomePhoto', [DocumentController::class, 'uploadHomePhoto'])->name('uploadHomePhoto');
        Route::delete('/member/deleteHomePhoto/{id}', [DocumentController::class, 'deleteHomePhoto'])->name('deleteHomePhoto');
    });
//admin
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/admin/profile_admin', [AdminController::class, 'profile_admin'])->name('profile_admin');
    });
});

