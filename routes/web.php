<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuctionController;
use App\Http\Controllers\Admin\BidController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Front Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| User Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (admin + super admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin|super admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Auctions
        Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');
        Route::get('/auctions/{auction}', [AuctionController::class, 'show'])->name('auctions.show');
        Route::delete('/auctions/{auction}', [AuctionController::class, 'destroy'])->name('auctions.destroy');
        Route::post('/auctions/{auction}/cancel', [AuctionController::class, 'cancel'])->name('auctions.cancel');

        // Bids
        Route::get('/bids', [BidController::class, 'index'])->name('bids.index');

        /*
        |--------------------------------------------------------------------------
        | Users CRUD (FULL: index, create, store, show, edit, update, delete)
        |--------------------------------------------------------------------------
        */
        Route::resource('users', UserController::class);

        // Soft delete extras
        Route::post('users/{user}/restore', [UserController::class, 'restore'])
            ->name('users.restore');

        Route::delete('users/{user}/force-delete', [UserController::class, 'forceDelete'])
            ->name('users.force_delete');

        // Payments
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');

        // Categories
        Route::resource('categories', CategoryController::class);

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings');

        // Admin Profile
        Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');

        // Blank Page
        Route::get('/blank', [DashboardController::class, 'blank'])->name('blank');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
