<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\AuthController as AdminAuthController;
use App\Http\Controllers\CekKesehatanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KaderController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\LansiaController;
use App\Http\Controllers\RiwayatKesehatanController;
use App\Models\CekKesehatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return view('layouts.app');
// });

// Route::get('/', [DashboardController::class, 'index'])->name('dashboard');



Route::middleware('guest')->group(function () {
    Route::get('/', [AdminAuthController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('login'); // route 'login' untuk GET form login di '/'

    // Tampilkan form login
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    // Proses login
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');

    // Register
    Route::get('/register', [AdminAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AdminAuthController::class, 'register'])->name('register.submit');

    // Verifikasi email
    Route::get('/verify/{id}/{hash}', [AdminAuthController::class, 'verifyEmail'])->name('verify.email');

    // Lupa password
    Route::get('/password/reset', [AdminAuthController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [AdminAuthController::class, 'sendResetLinkEmail'])->name('password.email');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout POST
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});


    
Route::middleware(['admin'])->group(function () {
    // Resource routes
    Route::resource('/lansia', LansiaController::class)->names('lansia');
    Route::resource('/jadwal', JadwalController::class)->names('jadwal');
    Route::resource('/kader', KaderController::class)->names('kader');

    // Kehadiran routes
    Route::post('/kehadiran/cetak-laporan-pdf', [KehadiranController::class, 'cetakLaporanPdf'])->name('kehadiran.cetakLaporanPdf');
    Route::post('/kehadiran/cetak-laporan-excel', [KehadiranController::class, 'cetakLaporanExcel'])->name('kehadiran.cetakLaporanExcel');
    Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadiran');
    Route::post('/kehadiran', [KehadiranController::class, 'store'])->name('kehadiran.store');

    // Cek Kesehatan routes
    Route::get('/kesehatan-cek', [CekKesehatanController::class, 'index'])->name('cekKesehatan.index');
    Route::get('/kesehatan-cek/jadwal', [CekKesehatanController::class, 'show'])->name('cekKesehatan.show');
    Route::get('/kesehatan-cek/create', [CekKesehatanController::class, 'create'])->name('cekKesehatan.create');
    Route::post('/kesehatan-cek/store', [CekKesehatanController::class, 'store'])->name('cekKesehatan.store');

    // Riwayat Kesehatan routes
    Route::get('/riwayat-kesehatan', [RiwayatKesehatanController::class, 'index'])->name('riwayatKesehatan.index');
    Route::get('/riwayat-kesehatan/list', [RiwayatKesehatanController::class, 'list'])->name('riwayatKesehatan.list');
    Route::get('/riwayat-kesehatan/show/{id}', [RiwayatKesehatanController::class, 'show'])->name('riwayatKesehatan.show');
    Route::get('/riwayat-kesehatan/jadwal/{jadwal_id}', [RiwayatKesehatanController::class, 'riwayatKesehatan'])->name('riwayatkesehatan.riwayatKesehatan');
    Route::get('riwayat-kesehatan/export-pdf', [RiwayatKesehatanController::class, 'exportPDF'])->name('riwayatKesehatan.exportPDF');
    Route::get('riwayat-kesehatan/export-excel', [RiwayatKesehatanController::class, 'exportExcel'])->name('riwayatKesehatan.exportExcel');

    // Berita routes
    Route::resource('berita', BeritaController::class);
    Route::delete('berita/{berita}', [BeritaController::class, 'destroy'])->name('berita.destroy');
});





// Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('Login');
// Route::post('/login', [AuthController::class, 'Login'])->name('Login');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::resource('/lansia', LansiaController::class)->names('lansia');
// Route::resource('/jadwal', JadwalController::class)->names('jadwal');
// Route::resource('/kader', KaderController::class)->names('kader');


// Route::post('/kehadiran/cetak-laporan-pdf', [KehadiranController::class, 'cetakLaporanPdf'])->name('kehadiran.cetakLaporanPdf');
// Route::post('/kehadiran/cetak-laporan-excel', [KehadiranController::class, 'cetakLaporanExcel'])->name('kehadiran.cetakLaporanExcel');


// Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadiran');
// Route::post('/kehadiran', [KehadiranController::class, 'store'])->name('kehadiran.store');


// Route::get('/kesehatan-cek', [CekKesehatanController::class, 'index'])->name('cekKesehatan.index');
// Route::get('/kesehatan-cek/jadwal', [CekKesehatanController::class, 'show'])->name('cekKesehatan.show');
// Route::get('/kesehatan-cek/create', [CekKesehatanController::class, 'create'])->name('cekKesehatan.create');
// Route::post('/kesehatan-cek/store', [CekKesehatanController::class, 'store'])->name('cekKesehatan.store');


// Route::get('/riwayat-kesehatan', [RiwayatKesehatanController::class, 'index'])->name('riwayatKesehatan.index');
// Route::get('/riwayat-kesehatan/list', [RiwayatKesehatanController::class, 'list'])->name('riwayatKesehatan.list');
// Route::get('/riwayat-kesehatan/show/{id}', [RiwayatKesehatanController::class, 'show'])->name('riwayatKesehatan.show');
// Route::get('/riwayat-kesehatan/jadwal/{jadwal_id}', [RiwayatKesehatanController::class, 'riwayatKesehatan'])->name('riwayatkesehatan.riwayatKesehatan');


// Route::resource('berita', BeritaController::class);
// Route::delete('berita/{berita}', [BeritaController::class, 'destroy'])->name('berita.destroy');


// Route::get('riwayat-kesehatan/export-pdf', [RiwayatKesehatanController::class, 'exportPDF'])->name('riwayatKesehatan.exportPDF');
// Route::get('riwayat-kesehatan/export-excel', [RiwayatKesehatanController::class, 'exportExcel'])->name('riwayatKesehatan.exportExcel');
