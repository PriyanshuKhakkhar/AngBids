<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuctionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\AuctionController as AdminAuctionController;
use App\Http\Controllers\Api\WebsiteController;

Route::post('/login', [AuthController::class, 'login']);

// Public Website Routes
Route::get('/home', [WebsiteController::class, 'index']);


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Auction Bids
    Route::get('auctions/{id}/bids', [AuctionController::class, 'bids']);

    Route::apiResource('auctions', AuctionController::class, ['as' => 'api']);

    // Admin API Routes
    Route::middleware(['role:admin|super admin'])->prefix('admin')->group(function () {

            // Category Management
            Route::apiResource('_categories', AdminCategoryController::class);
            // Auction Management
            Route::apiResource('_auctions', AdminAuctionController::class);

            // Custom Category Actions
            Route::post('_categories/{id}/restore', [AdminCategoryController::class, 'restore']);
            Route::delete('_categories/{id}/force-delete', [AdminCategoryController::class, 'forceDelete']);

            // Custom Auction Actions 
            Route::post('_auctions/{id}/restore', [AdminAuctionController::class, 'restore']);
            Route::delete('_auctions/{id}/force-delete', [AdminAuctionController::class, 'forceDelete']);
            Route::post('_auctions/{id}/approve', [AdminAuctionController::class, 'approve']);
            Route::post('_auctions/{id}/cancel', [AdminAuctionController::class, 'cancel']);
        });
});
