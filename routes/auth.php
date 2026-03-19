<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store'])
        ->middleware('throttle:10,1');
    
    Route::post('register/send-otp', [RegisteredUserController::class, 'sendOtp'])
        ->middleware('throttle:5,1')
        ->name('register.send_otp');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('password.email');

    Route::get('forgot-password/verify-otp', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showVerifyForm'])
        ->name('password.verify-otp');

    Route::post('forgot-password/verify-otp', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'verifyOtp'])
        ->middleware('throttle:10,1');

    Route::post('forgot-password/resend-otp', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'resendOtp'])
        ->middleware('throttle:5,1')
        ->name('password.resend-otp');

    Route::get('forgot-password/reset-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])
        ->name('password.reset-form');

    Route::post('forgot-password/reset-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetPassword'])
        ->name('password.update-new');

});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('logout', [AuthenticatedSessionController::class, 'destroy']);
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
