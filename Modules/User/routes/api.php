<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;
use Modules\User\Http\Controllers\AuthController;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('/me', function () {
        return auth()->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('users', UserController::class)->names('users');
});
