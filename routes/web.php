<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\BeritaController;
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

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('Login');
Route::post('/login', [AuthController::class, 'Login'])->name('Login');

Route::resource('/lansia', LansiaController::class)->names('lansia');
Route::resource('/jadwal', JadwalController::class)->names('jadwal');
Route::resource('/kader', KaderController::class)->names('kader');

// Route::resource('jadwal', JadwalController::class)->only([
//     'index', 'create', 'store', 'edit', 'update', 'destroy'
// ]);


Route::post('/kehadiran/cetak-laporan-pdf', [KehadiranController::class, 'cetakLaporanPdf'])->name('kehadiran.cetakLaporanPdf');
Route::post('/kehadiran/cetak-laporan-excel', [KehadiranController::class, 'cetakLaporanExcel'])->name('kehadiran.cetakLaporanExcel');


Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadiran');
Route::post('/kehadiran', [KehadiranController::class, 'store'])->name('kehadiran.store');


Route::get('/kesehatan-cek', [CekKesehatanController::class, 'index'])->name('cekKesehatan.index');
Route::get('/kesehatan-cek/jadwal', [CekKesehatanController::class, 'show'])->name('cekKesehatan.show');
Route::get('/kesehatan-cek/create', [CekKesehatanController::class, 'create'])->name('cekKesehatan.create');
Route::post('/kesehatan-cek/store',[CekKesehatanController::class, 'store'])->name('cekKesehatan.store');


Route::get('/riwayat-kesehatan', [RiwayatKesehatanController::class, 'index'])->name('riwayatKesehatan.index');
Route::get('/riwayat-kesehatan/list', [RiwayatKesehatanController::class, 'list'])->name('riwayatKesehatan.list');
Route::get('/riwayat-kesehatan/show/{id}', [RiwayatKesehatanController::class, 'show'])->name('riwayatKesehatan.show');

Route::resource('berita', BeritaController::class);
Route::delete('berita/{berita}', [BeritaController::class, 'destroy'])->name('berita.destroy');


Route::get('cek-kesehatan/export-pdf', [CekKesehatanController::class, 'exportPDF'])->name('cekKesehatan.exportPDF');
Route::get('cek-kesehatan/export-excel', [CekKesehatanController::class, 'exportExcel'])->name('cekKesehatan.exportExcel');