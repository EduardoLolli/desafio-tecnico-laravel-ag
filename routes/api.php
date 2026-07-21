<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductFamilyController;
use App\Http\Middleware\CheckAuthMode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware([CheckAuthMode::class])->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::prefix('product-families')->group(function () {
        Route::post('/add-new', [ProductFamilyController::class, 'createProductFamily']);
        Route::post('/update', [ProductFamilyController::class, '']);
        Route::post('/delete', [ProductFamilyController::class, '']);
        Route::post('/list', [ProductFamilyController::class, '']);
    });

    Route::prefix('products')->group(function () {
        Route::post('/add-new', [ProductController::class, '']);
        Route::post('/update', [ProductController::class, '']);
        Route::post('/delete', [ProductController::class, '']);
        Route::post('/list', [ProductController::class, '']);
    });
});
