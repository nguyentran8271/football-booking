<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/logout', function() { return redirect('/'); });

// Register routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Forgot Password routes (3 bước: email -> OTP -> mật khẩu mới)
Route::get('/forgot-password',        [ForgotPasswordController::class, 'showStep1'])->name('password.step1');
Route::post('/forgot-password',       [ForgotPasswordController::class, 'postStep1'])->name('password.step1.post');
Route::get('/forgot-password/otp',    [ForgotPasswordController::class, 'showStep2'])->name('password.step2');
Route::post('/forgot-password/otp',   [ForgotPasswordController::class, 'postStep2'])->name('password.step2.post');
Route::get('/forgot-password/reset',  [ForgotPasswordController::class, 'showStep3'])->name('password.step3');
Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'postStep3'])->name('password.step3.post');
Route::post('/forgot-password/resend',[ForgotPasswordController::class, 'resendOtp'])->name('password.resend');
