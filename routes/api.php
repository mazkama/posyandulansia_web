<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\LansiaController;
use App\Http\Controllers\Api\KaderController;
use App\Http\Controllers\Api\CekKesehatanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Api\KehadiranController;
use App\Http\Controllers\Api\BeritaController;
use App\Http\Controllers\Api\NotifikasiController as NotifControlller;
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

//Route Auth
Route::post('login', [AuthController::class, 'login']);
Route::get('check-token', [AuthController::class, 'checkToken'])->middleware('auth:sanctum');
Route::post('update-password', [AuthController::class, 'updatePassword'])->middleware('auth:sanctum');

//Route Jadwal
Route::get('cekJadwal', [JadwalController::class, 'cekJadwal']);
Route::get('jadwal', [JadwalController::class, 'index']);

//Route Kehadiran
Route::get('/kehadiran/jadwal/{jadwal_id}', [KehadiranController::class, 'getLansiaByJadwal']);

//Route Lansia
Route::get('getLansias', [LansiaController::class, 'index']);
Route::get('lansiaSearch', [LansiaController::class, 'searchLansia']);
Route::get('lansia/{userId}', [LansiaController::class, 'show']);

//Route Kader
Route::get('kader/{userId}', [KaderController::class, 'show']);

//Route Cek Kesehatan
Route::post('cek-kesehatan', [CekKesehatanController::class, 'store']);
Route::get('cek-kesehatan', [CekKesehatanController::class, 'getByLansiaId']);
Route::get('cek-kesehatan/{lansia_id}/parameter/{parameter}', [CekKesehatanController::class, 'getKesehatanParameter']);

//Route Berita
Route::get('/berita', [BeritaController::class, 'index']);

//Route Notifikasi
Route::post('/send-notification', [NotifikasiController::class, 'sendToTopic']);
Route::get('/getNotifikasi', [NotifControlller::class, 'getNotifikasi']);



Route::delete('/firebase/users', [NotifikasiController::class, 'deleteAllFirebaseUsers']);
