<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\Controller;

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

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/auth-login', [LoginController::class, 'loginUser']);
});
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');