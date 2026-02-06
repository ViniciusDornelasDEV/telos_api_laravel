<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;
use Modules\User\Http\Controllers\AuthController;
use Modules\User\Http\Controllers\SupplierUserController;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('/me', function () {
        return auth()->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'insert']);
    Route::put('/users/{user}', [UserController::class, 'update']);

    Route::prefix('suppliers')->group(function () {
        Route::get('{supplier}/users', [SupplierUserController::class, 'index']);
        Route::post('{supplier}/users/{user}', [SupplierUserController::class, 'attach']);
        Route::delete('{supplier}/users/{user}', [SupplierUserController::class, 'detach']);
    });

});
