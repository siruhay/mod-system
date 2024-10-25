<?php

use Illuminate\Support\Facades\Route;
use Module\System\Http\Controllers\DashboardController;
use Module\System\Http\Controllers\SystemUserLogController;
use Module\System\Http\Controllers\AuthenticatedSessionController;
use Module\System\Http\Controllers\TwoFactorAuthenticationController;

Route::get('dashboard', [DashboardController::class, 'accountIndex']);

Route::get('setting', [DashboardController::class, 'setting']);
Route::post('setting/confirmed-factor-authentication', [TwoFactorAuthenticationController::class, 'confirm']);
Route::post('setting/update-password', [TwoFactorAuthenticationController::class, 'updatePassword']);
Route::post('setting/update-profile', [TwoFactorAuthenticationController::class, 'updateProfile']);
Route::post('setting/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store']);
Route::post('setting/logout-other-devices', [DashboardController::class, 'logoutOtherDevices']);
Route::delete('setting/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy']);
Route::get('setting/two-factor-qr-code', [TwoFactorAuthenticationController::class, 'show']);

Route::post('impersonate-take/{sourceId}', [AuthenticatedSessionController::class, 'impersonateTake']);
Route::post('impersonate-leave', [AuthenticatedSessionController::class, 'impersonateLeave']);
Route::post('user-geodata', [AuthenticatedSessionController::class, 'update']);
Route::get('user-data', [AuthenticatedSessionController::class, 'show']);
Route::get('user-modules', [AuthenticatedSessionController::class, 'userModules']);
Route::get('services', [AuthenticatedSessionController::class, 'userModules']);
Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);
Route::resource('activity', SystemUserLogController::class)->parameters(['activity' => 'systemUserLog']);
