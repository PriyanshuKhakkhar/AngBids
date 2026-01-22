<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'role'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/auctions', [AdminController::class, 'auctions'])->name('auctions.index');
    Route::get('/bids', [AdminController::class, 'bids'])->name('bids.index');
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments.index');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications');
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::get('/blank', [AdminController::class, 'blank'])->name('blank');

});

require __DIR__.'/auth.php';
