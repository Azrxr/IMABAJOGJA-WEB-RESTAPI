<?php

use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\StudyPlaneController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\WilayahController;

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
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/wilayah', [HomeController::class, 'wilayah'])->name('wilayah');
    Route::get('/province', [WilayahController::class, 'province'])->name('province');
    Route::get('/regency/{id}', [WilayahController::class, 'regency'])->name('regency');
    Route::get('/district/{id}', [WilayahController::class, 'district'])->name('district');
    
    Route::get('/university', [StudyPlaneController::class, 'university'])->name('university');
    Route::get('/programStudy/{id}', [StudyPlaneController::class, 'programStudy'])->name('programStudy');
    Route::get('/faculty/{id}', [StudyPlaneController::class, 'faculty'])->name('faculty');

    Route::get('/universities', [HomeController::class, 'universities'])->name('universities');
    Route::get('/programStudy', [HomeController::class, 'programStudy'])->name('programStudy');
    // Autentikasi API menggunakan token
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/login', [LoginController::class, 'loginPage'])->name('login');
    Route::get('/register', [RegisterController::class, 'registerPage'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/admin/register', [RegisterController::class, 'Register_admin'])->name('register_admin');
    Route::get('/verify-email/{token}', [RegisterController::class, 'verifyEmail'])->name('verify-email');
    Route::post('/resend-verification-email', [RegisterController::class, 'resendVerificationEmail'])->name('resend-verification-email');
    Route::get('/logout', [LoginController::class, 'logout']);
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

        //studyplane
        Route::get('/member/studyPlane', [StudyPlaneController::class, 'index'])->name('studyplane');
        Route::post('/member/studyPlaneAdd', [StudyPlaneController::class, 'studyPlaneAdd'])->name('studyPlaneAdd');
        Route::delete('/member/studyPlaneDelete/{id}', [StudyPlaneController::class, 'studyPlaneDelete'])->name('studyPlaneDelete');
    });
    //admin
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/admin/profile_admin', [AdminController::class, 'profile_admin'])->name('profile_admin');
        Route::post('/admin/updateProfile', [AdminController::class, 'pdateProfile'])->name('updateProfile');

        // organozationProfile
        Route::post('/imaba/profileUpdate', [HomeController::class, 'editProfile'])->name('editProfile');
        Route::post('/imaba/addFile', [HomeController::class, 'addFile'])->name('addFile');
        Route::post('/imaba/updateFile/{id}', [HomeController::class, 'updateFile'])->name('updateFile');
        Route::delete('/imaba/deleteFile/{id}', [HomeController::class, 'deleteFile'])->name('deleteFile');

        //studyplane
        Route::get('/admin/getAllStudyPlans', [StudyPlaneController::class, 'getAllStudyPlans'])->name('getAllStudyPlans');
        Route::post('/admin/studyPlaneAdd', [StudyPlaneController::class, 'adminStudyPlaneAdd']);
        Route::post('/admin/studyPlaneUpdate/{id}', [StudyPlaneController::class, 'adminStudyPlaneUpdate']);
        Route::delete('/admin/studyPlaneDelete/{id}', [StudyPlaneController::class, 'adminStudyPlaneDelete']);

        //member
        Route::post('/admin/createMember', [MemberController::class, 'createMember'])->name('createMember');
    });
});
