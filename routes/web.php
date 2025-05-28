<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\DeleteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [Controller::class, 'welcome'])->name('welcome');

// Untuk halaman login (GET)
Route::get('/login', [LoginController::class, 'loginPage'])->name('login');

// Untuk proses login (POST)
Route::post('/login', [LoginController::class, 'login'])->name('auth-login');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/delete-account', [DeleteController::class, 'showDeleteForm'])->name('delete.account.form');
    Route::post('/delete-account', [DeleteController::class, 'destroyAccount'])->name('delete.account');