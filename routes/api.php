<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuctionController;

// Get all auctions
Route::get('/auctions', [AuctionController::class, 'index']);

// Get single auction
Route::get('/auctions/{id}', [AuctionController::class, 'show']);
