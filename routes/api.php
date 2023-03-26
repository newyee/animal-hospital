<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\MeController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\EmailChangeController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\ReserveCancelController;
use App\Http\Controllers\Api\ReserveController;
use App\Http\Controllers\Api\ReservedInfoController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// 認証不要のルート
Route::post('/login', [LoginController::class, 'handleLogin'])->name('handleLogin');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/register/verify', [RegisterController::class, 'verify'])->name('verify');
Route::post('/reservation/confirm', [ReserveController::class, 'confirm'])->name('confirm');
Route::post('/reservation/decide', [ReserveController::class, 'decide'])->name('decide');
Route::post('/reservation/cancel', [ReserveCancelController::class, 'cancel'])->name('cancel');
Route::post('/reservation/cancel/info', [ReserveCancelController::class, 'cancelInfo'])->name('cancelInfo');
Route::post('/reserved/info', [ReservedInfoController::class, 'reserveList'])->name('reserveList');
Route::get('/reserved/time/list', [ReservedInfoController::class, 'reservedTimeList'])->name('reservedTimeList');

Route::post('/user/password/reset', [ResetPasswordController::class, 'sendPasswordResetLink'])->name('sendPasswordResetLink');
Route::post('/user/password/verify', [ResetPasswordController::class, 'verifyPasswordToken'])->name('verifyPasswordToken');
Route::post('/user/password/change', [ResetPasswordController::class, 'resetPassword'])->name('resetPassword');

// meでは空の値を返す必要がある為、認証不要にする
Route::get('/me', [MeController::class, 'handle'])->name('me');

// 認証が必要なルート
Route::middleware('jwt')->group(function () {
    Route::delete('/logout', [LogoutController::class, 'handle'])->name('logout');

    Route::prefix('user')->group(function () {
        Route::put('update', [UserController::class, 'update'])->name('update');
        Route::put('email/update', [EmailChangeController::class, 'updateEmail'])->name('updateEmail');
        Route::post('email/change/verify', [EmailChangeController::class, 'verifyEmail'])->name('verifyEmail');
        Route::get('email/resend', [EmailChangeController::class, 'resendEmail'])->name('resendEmail');
        Route::delete('email/change/cancel', [EmailChangeController::class, 'cancelChangeEmail'])->name('cancelChangeEmail');
        Route::put('password/update', [PasswordController::class, 'updatePassword'])->name('updatePassword');
        Route::delete('delete', [UserController::class, 'delete'])->name('delete');
    });
});
