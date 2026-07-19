<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\SpaProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/spa/{slug}', [SpaProfileController::class, 'show'])->name('spa.show');

Route::get('/legal/terms', [LegalController::class, 'terms'])->name('legal.terms');
Route::get('/legal/privacy', [LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('/legal/cookies', [LegalController::class, 'cookies'])->name('legal.cookies');

// Planned public sections that are not built yet render a shared
// coming-soon page so header navigation never leads to a 404.
foreach ([
    'directory' => 'navigation.directory',
    'article' => 'navigation.articles',
    'campus' => 'navigation.campus',
    'promo' => 'navigation.promos',
] as $path => $sectionKey) {
    Route::view("/{$path}", 'coming-soon', ['sectionKey' => $sectionKey])->name(str_replace('-', '_', $path).'.index');
}

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])
        ->middleware('throttle:5,1')
        ->name('password.update');
});

Route::post('/logout', [LogoutController::class, 'store'])->middleware('auth')->name('logout');

Route::get('/verify-email', [EmailVerificationController::class, 'notice'])->name('verification.notice');
Route::get('/verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware('signed')
    ->name('verification.verify');
Route::post('/verify-email/resend', [EmailVerificationController::class, 'resend'])
    ->middleware('throttle:6,1')
    ->name('verification.resend');
