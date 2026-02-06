<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;
use Modules\Product\Http\Controllers\ProductImportController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'insert']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    
    Route::post('products/import', [ProductImportController::class, 'import']);
});
