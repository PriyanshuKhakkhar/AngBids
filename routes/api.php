<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuctionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\WebsiteController;
use App\Http\Controllers\Api\WatchlistController;

Route::post('/login', [AuthController::class, 'login']);

// Public Website Routes
Route::get('/home', [WebsiteController::class, 'index']);


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Auction Bids
    Route::get('auctions/{id}/bids', [AuctionController::class, 'bids']);
    Route::post('auctions/{id}/bid', [AuctionController::class, 'placeBid']);

    Route::apiResource('auctions', AuctionController::class, ['as' => 'api']);

    // Watchlist
    Route::get('watchlist', [WatchlistController::class, 'index']);
    Route::post('watchlist/{auction_id}', [WatchlistController::class, 'store']);
    Route::delete('watchlist/{auction_id}', [WatchlistController::class, 'destroy']);

    // Admin API Routes
    Route::middleware(['role:admin|super admin'])->prefix('admin')->group(function () {

            // Category Management
            Route::apiResource('_categories', AdminCategoryController::class);

            // Custom Category Actions
            Route::post('_categories/{id}/restore', [AdminCategoryController::class, 'restore']);
            Route::delete('_categories/{id}/force-delete', [AdminCategoryController::class, 'forceDelete']);
        });
});
