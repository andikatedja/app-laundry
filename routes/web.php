<?php

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
    Route::get('login', 'Auth@index')->middleware('islogin');
    Route::post('login', 'Auth@auth');
    Route::get('register', 'Auth@registerView');
    Route::post('register', 'Auth@register');
    Route::get('logout', 'Auth@logout');
});


// Admin routes
Route::group(['prefix' => 'admin', 'middleware' => ['language', 'isadmin']], function () {
    Route::get('/', 'Admin@index');
});

//Member routes
Route::group(['prefix' => 'member', 'middleware' => ['language', 'ismember']], function () {
    Route::get('/', 'Member@index');
    Route::get('harga', 'Member@harga');
    Route::get('poin', 'Member@poin');
    Route::get('edit', 'Member@edit');
    Route::get('edit/resetfoto', 'Member@resetfoto');
    Route::patch('edit', 'Member@editprofil');
    Route::patch('edit/password', 'Member@editpassword');
    Route::get('transaksi', 'Member@riwayatTransaksi');
    Route::get('saran', 'Member@saranKomplain');
});

// Set language route
Route::get('/{locale}', function ($locale, Request $request) {
    if (!in_array($locale, ['en', 'id'])) {
        abort(404);
    }
    $request->session()->put('locale', $locale);
    return redirect()->back();
});
