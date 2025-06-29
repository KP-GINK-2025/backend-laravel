<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\klasifikasiInstansi as KlasifikasiInstansi;

Route::apiResource('bidang', KlasifikasiInstansi\BidangController::class);
Route::apiResource('unit', KlasifikasiInstansi\UnitController::class);
Route::apiResource('subunit', KlasifikasiInstansi\SubUnitController::class);
Route::apiResource('upb', KlasifikasiInstansi\UpbController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
