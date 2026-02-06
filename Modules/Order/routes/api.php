<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
     Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::put('{order}', [OrderController::class, 'update']);
    });
});
