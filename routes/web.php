<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin as Admin;
use App\Http\Controllers\Admin\klasifikasiInstansi as KlasifikasiInstansi;
use App\Http\Controllers\Admin\klasifikasiAset as klasifikasiAset;
use App\Http\Controllers\FrontendController;

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



Route::middleware(['guest'])->group(function () {
});

Route::get('/', [FrontendController::class, 'index']);

Route::get('/admin/login', [Admin\AuthController::class, 'index'])->name('admin.login');
Route::post('/admin/sign-in', [Admin\AuthController::class, 'authenticate']);
Route::post('/admin/logout', [Admin\AuthController::class, 'logout']);

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('admin.index');
    Route::put('settings/update-identity', [Admin\SettingController::class, 'update_identity']);
    Route::resource('settings', Admin\SettingController::class);
    Route::get('dashboard', [Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('roles/select2', [Admin\RoleController::class, 'select2']);
    Route::resource('users/roles', Admin\RoleController::class);
    Route::post('users/resetpassword', [Admin\UserController::class, 'resetpassword']);
    Route::resource('users', Admin\UserController::class);

    Route::resource('bidang', KlasifikasiInstansi\BidangController::class);
    Route::resource('unit', KlasifikasiInstansi\UnitController::class);
    Route::resource('subunit', KlasifikasiInstansi\SubUnitController::class);
    Route::resource('upb', KlasifikasiInstansi\UpbController::class);

    Route::resource('akunaset', klasifikasiAset\AkunAsetController::class);
    Route::resource('kelompokaset', klasifikasiAset\KelompokAsetController::class);
    Route::resource('jenisaset', klasifikasiAset\JenisAsetController::class);

    Route::get('profile', [Admin\ProfileController::class, 'index']);
    Route::put('profile/{id}', [Admin\ProfileController::class, 'update']);
    Route::get('profile/edit', [Admin\ProfileController::class, 'edit']);
    Route::get('profile/edit-password', [Admin\ProfileController::class, 'edit_password']);
    Route::put('profile/change-password/{id}', [Admin\ProfileController::class, 'change_password']);
});

// require __DIR__ . '/auth.php';