<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductFamilyController;
use App\Http\Middleware\CheckAuthMode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware([CheckAuthMode::class])->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::apiResource('product-families', ProductFamilyController::class);
    Route::apiResource('products', ProductController::class);

    Route::delete('products/remove/{identifier}', [ProductController::class, 'removeStock']);
});
