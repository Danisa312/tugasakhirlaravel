<?php

use App\Http\Controllers\API\PendapatanController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\KategoriPengeluaranController;
use App\Http\Controllers\API\PengeluaranController;
use App\Http\Controllers\API\SaldoController;
use App\Http\Controllers\API\LaporanBulananController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\AuthController;
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
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('pendapatan', PendapatanController::class);
    Route::apiResource('kategori_pengeluaran', KategoriPengeluaranController::class);
    Route::apiResource('pengeluaran', PengeluaranController::class);
    Route::apiResource('saldo', SaldoController::class);
    Route::apiResource('laporan_bulanan', LaporanBulananController::class);
    Route::apiResource('settings', SettingController::class);
});
