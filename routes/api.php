<?php

use Illuminate\Support\Facades\Route;

// Website Controllers
use App\Http\Controllers\Api\Website\AuthController;
use App\Http\Controllers\Api\Website\WebsiteController;

// User Controllers
use App\Http\Controllers\Api\User\AuctionController;
use App\Http\Controllers\Api\User\WatchlistController;

// Admin Controllers
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\AuctionController as AdminAuctionController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\ContactController as AdminContactController;


Route::post('/login', [AuthController::class, 'login']);

// Public Search & Filter Endpoint
Route::get('/auctions/search', [AuctionController::class, 'search']);

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
            // Auction Management
            Route::apiResource('_auctions', AdminAuctionController::class);
            // User Management
            Route::apiResource('_users', AdminUserController::class);
            // Contact Management
            Route::apiResource('_contacts', AdminContactController::class);

            // Custom Category Actions
            Route::post('_categories/{id}/restore', [AdminCategoryController::class, 'restore']);
            Route::delete('_categories/{id}/force-delete', [AdminCategoryController::class, 'forceDelete']);

            // Custom Auction Actions
            Route::post('_auctions/{id}/restore', [AdminAuctionController::class, 'restore']);
            Route::delete('_auctions/{id}/force-delete', [AdminAuctionController::class, 'forceDelete']);
            Route::post('_auctions/{id}/approve', [AdminAuctionController::class, 'approve']);
            Route::post('_auctions/{id}/cancel', [AdminAuctionController::class, 'cancel']);

            // Custom User Actions
            Route::post('_users/{id}/restore', [AdminUserController::class, 'restore']);
            Route::delete('_users/{id}/force-delete', [AdminUserController::class, 'forceDelete']);

            // Custom Contact Actions
            Route::post('_contacts/{id}/restore', [AdminContactController::class, 'restore']);
            Route::delete('_contacts/{id}/force-delete', [AdminContactController::class, 'forceDelete']);
        });
});
