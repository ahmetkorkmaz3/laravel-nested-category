<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryAuditingController;
use App\Http\Controllers\ProductAuditingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('categories')->group(function () {
    Route::get('', [CategoryController::class, 'index']);
    Route::post('', [CategoryController::class, 'store']);

    Route::prefix('/{category}')->group(function () {
        Route::get('', [CategoryController::class, 'show']);
        Route::put('', [CategoryController::class, 'update']);
        Route::delete('', [CategoryController::class, 'destroy']);

        Route::get('auditing', CategoryAuditingController::class);

        Route::prefix('products')->group(function () {
            Route::get('', [ProductController::class, 'index']);
            Route::post('', [ProductController::class, 'store']);
        });
    });
});

Route::prefix('products/{product}')->group(function () {
    Route::get('', [ProductController::class, 'show']);
    Route::put('', [ProductController::class, 'update']);
    Route::delete('', [ProductController::class, 'destroy']);

    Route::get('auditing', ProductAuditingController::class);
});
