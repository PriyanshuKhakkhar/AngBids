<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuctionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::apiResource('auctions', AuctionController::class);

    // Admin API Routes
    Route::middleware(['role:admin|super admin'])
        ->prefix('admin')
        ->group(function () {

            // Category Management
            Route::apiResource('_categories', AdminCategoryController::class);

            // Custom Category Actions
            Route::post('_categories/{id}/restore', [AdminCategoryController::class, 'restore']);
            Route::delete('_categories/{id}/force-delete', [AdminCategoryController::class, 'forceDelete']);
        });
});
