<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuctionController;

Route::apiResource('auctions', AuctionController::class);
