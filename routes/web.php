<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuctionController as AdminAuctionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BidController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\NotificationController;

// Public Website Routes
Route::get('/', [WebsiteController::class, 'index'])->name('home');
Route::get('/about', [WebsiteController::class, 'about'])->name('about');
Route::get('/contact', [WebsiteController::class, 'contact'])->name('contact');
Route::post('/contact', [WebsiteController::class, 'contactStore'])->name('contact.store');

// Auction Routes (Public)
Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');
Route::get('/auctions/{id}', [AuctionController::class, 'show'])->name('auctions.show');
Route::get('/search', [AuctionController::class, 'search'])->name('auctions.search');

// Smart Dashboard Redirect - Redirects to appropriate dashboard based on user role
Route::get('/dashboard', function () {
    if (auth()->check()) {
        // Check if user is admin or super admin
        if (auth()->user()->role === 'admin' || auth()->user()->role === 'super admin') {
            return redirect()->route('admin.dashboard');
        }
        // Regular user
        return redirect()->route('user.dashboard');
    }
    return redirect()->route('login');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // User Dashboard Routes
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::get('/my-bids', [UserDashboardController::class, 'myBids'])->name('my-bids');
        Route::get('/winning-items', [UserDashboardController::class, 'winningItems'])->name('winning-items');
        Route::get('/wishlist', [UserDashboardController::class, 'wishlist'])->name('wishlist');
        Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
    });
});

// Admin Routes
Route::middleware(['auth', 'role:admin|super admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/auctions', [AdminAuctionController::class, 'index'])->name('auctions.index');
    Route::get('/auctions/{auction}', [AdminAuctionController::class, 'show'])->name('auctions.show');
    Route::delete('/auctions/{auction}', [AdminAuctionController::class, 'destroy'])->name('auctions.destroy');
    Route::post('/auctions/{auction}/cancel', [AdminAuctionController::class, 'cancel'])->name('auctions.cancel');
    Route::get('/bids', [BidController::class, 'index'])->name('bids.index');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/blank', [DashboardController::class, 'blank'])->name('blank');

});

require __DIR__.'/auth.php';
