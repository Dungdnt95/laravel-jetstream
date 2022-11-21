<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\User\TopController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('login.index'));
});
Route::resource('login', LoginController::class);
Route::resource('forgot-password', ForgotPasswordController::class);
Route::resource('init-password', ResetPasswordController::class);
Route::get('logout', [LoginController::class, 'logout']);

Route::group([
    'middleware' => ['assign.guard:admin', 'admin'],
    'prefix' => 'admin',
    'as' => 'admin.',
], function () {
    Route::resource('dashboard', DashboardController::class);
    Route::resource('user', UserController::class);
    Route::post('check-email', [UserController::class, 'checkEmail'])->name('user.checkEmail');
});

Route::group([
    'middleware' => ['user'],
    'as' => 'user.',
], function () {
    Route::resource('top', TopController::class);
});
