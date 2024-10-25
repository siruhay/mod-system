<?php

use Illuminate\Support\Facades\Route;
use Module\System\Http\Controllers\AuthenticatedSessionController;

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware([
        'guest:web',
        'throttle:login'
    ]);

Route::post('/login-challenge', [AuthenticatedSessionController::class, 'challenge'])
    ->middleware([
        'guest:web',
        'throttle:login'
    ]);

Route::post('/login-finder', [AuthenticatedSessionController::class, 'userfind'])
    ->middleware([
        'guest:web',
        'throttle:login'
    ]);

Route::post('/reset-password', [AuthenticatedSessionController::class, 'resetpass'])
    ->middleware([
        'guest:web',
        'throttle:login'
    ]);