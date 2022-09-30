<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
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
});
Route::group(['middleware' => 'language'], function () {
    Route::get('register', [RegisterController::class, 'show'])->name('register.show');
    Route::post('register', [RegisterController::class, 'register'])->name('register.register');
    Route::get('register-admin', [RegisterController::class, 'registerAdminView'])->name('register.admin');
    Route::post('register-admin', [RegisterController::class, 'registerAdmin'])->name('register.register_admin');
});


// Admin routes
Route::group([
    'prefix' => 'admin',
    'middleware' => ['language', 'islogin'],
    'controller' => AdminController::class
], function () {
    Route::get('/', 'index');
    Route::get('input-transaksi', 'inputTransaksi');
    Route::get('transaksi', 'riwayatTransaksi');
    Route::get('hapus-sesstransaksi', 'hapusSessTransaksi');
    Route::get('hapus-transaksi/{row_id}', 'hapusTransaksi');
    Route::post('simpan-transaksi', 'simpanTransaksi');
    Route::post('tambah-transaksi', 'tambahTransaksi');
    Route::post('ambil-detail-transaksi', 'ambilDetailTransaksi');
    Route::post('ubah-status-transaksi', 'ubahStatusTransaksi');
    Route::get('cetak-transaksi/{id}', 'cetakTransaksi');
    Route::get('harga', 'harga');
    Route::post('ambil-harga', 'ambilHarga');
    Route::post('tambah-harga', 'tambahHarga');
    Route::post('ubah-harga', 'ubahHarga');
    Route::post('tambah-barang', 'tambahBarang');
    Route::post('tambah-servis', 'tambahServis');
    Route::get('members', 'members');
    Route::get('voucher', 'voucher');
    Route::post('voucher/tambah', 'tambahVoucher');
    Route::post('voucher/ubahaktif', 'ubahAktifVoucher');
    Route::get('saran', 'saran');
    Route::post('ambil-sarankomplain', 'ambilSaranKomplain');
    Route::post('kirim-balasan', 'kirimBalasan');
    Route::get('laporan', 'laporan');
    Route::post('get-month', 'getMonth');
    Route::post('cetak-laporan', 'cetakLaporan');
    Route::get('laporanview', 'laporanview');
    Route::get('get-service-type', 'getServiceType');
    Route::patch('update-service-type', 'updateServiceType');
});

//Member routes
Route::group([
    'prefix' => 'member',
    'middleware' => ['language', 'islogin'],
    'controller' => MemberController::class
], function () {
    Route::get('/', 'index')->name('member.index');
    Route::get('price-lists', 'priceLists')->name('member.price_lists');
    Route::get('point', 'point')->name('member.point');
    Route::get('point/redeem/{voucher}', 'redeemVoucher')->name('member.redeem');
    Route::get('transactions', 'transactionsHistory')->name('member.transactions');
    Route::get('transactions/{id}', 'transactionsDetail')->name('member.transactions.detail');
    Route::get('complaint-suggestions', 'complaintSuggestions')->name('member.complaint');
    Route::post('complaint-suggestions', 'sendComplaintSuggestions')->name('member.complaint_send');
});

// User profile routes
Route::group([
    'prefix' => 'profile',
    'middleware' => ['language', 'islogin'],
    'controller' => UserController::class
], function () {
    Route::get('/', 'index')->name('profile.index');
    Route::get('reset-photo', 'resetPhoto')->name('profile.reset_photo');
    Route::patch('/', 'update')->name('profile.update');
    Route::patch('password', 'updatePassword')->name('profile.update_password');
});

// Set language route
Route::get('/{locale}', function ($locale, Request $request) {
    if (!in_array($locale, ['en', 'id'])) {
        abort(404);
    }
    $request->session()->put('locale', $locale);
    return redirect()->back();
});
