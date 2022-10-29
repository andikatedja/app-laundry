<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\ProfilePasswordController;
use App\Http\Controllers\Profile\ProfilePhotoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

// Home route
Route::get('/', function () {
    return view('landing');
})->middleware('language');

// Auth routes
Route::group(['middleware' => 'language'], function () {
    Route::get('login', [LoginController::class, 'show'])->name('login.show')->middleware('islogin');
    Route::post('login', [LoginController::class, 'authenticate'])->name('login.authenticate');
    Route::get('logout', [LoginController::class, 'logout'])->name('login.logout');

    Route::get('register', [RegisterController::class, 'show'])->name('register.show');
    Route::post('register', [RegisterController::class, 'register'])->name('register.register');
    Route::get('register-admin', [RegisterController::class, 'registerAdminView'])->name('register.admin');
    Route::post('register-admin', [RegisterController::class, 'registerAdmin'])->name('register.register_admin');
});

// User profile routes
Route::group([
    'prefix' => 'profile',
    'middleware' => ['language', 'islogin'],
], function () {
    Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/photo/delete', [ProfilePhotoController::class, 'destroy'])->name('profile.photo.destroy');
    Route::patch('/password', [ProfilePasswordController::class, 'update'])->name('profile.password.update');
});

// Set language route
Route::get('/{locale}', LocaleController::class);
