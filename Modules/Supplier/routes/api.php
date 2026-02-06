<?php

use Illuminate\Support\Facades\Route;
use Modules\Supplier\Http\Controllers\SupplierController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('/suppliers', [SupplierController::class, 'index']);
    Route::post('/suppliers', [SupplierController::class, 'insert']);
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update']);
});
