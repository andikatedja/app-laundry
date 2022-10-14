<?php

use App\Http\Controllers\AdminController;

Route::group([
    'middleware' => ['language', 'islogin'],
    'controller' => AdminController::class
], function () {
    Route::get('/', 'index');
    Route::get('/input-transaksi', 'inputTransaksi');
    Route::get('/transaksi', 'riwayatTransaksi');
    Route::get('/hapus-sesstransaksi', 'hapusSessTransaksi');
    Route::get('/hapus-transaksi/{row_id}', 'hapusTransaksi');
    Route::post('/simpan-transaksi', 'simpanTransaksi');
    Route::post('/tambah-transaksi', 'tambahTransaksi');
    Route::post('/ambil-detail-transaksi', 'ambilDetailTransaksi');
    Route::post('/ubah-status-transaksi', 'ubahStatusTransaksi');
    Route::get('/cetak-transaksi/{id}', 'cetakTransaksi');
    Route::get('/harga', 'harga');
    Route::post('/ambil-harga', 'ambilHarga');
    Route::post('/tambah-harga', 'tambahHarga');
    Route::post('/ubah-harga', 'ubahHarga');
    Route::post('/tambah-barang', 'tambahBarang');
    Route::post('/tambah-servis', 'tambahServis');
    Route::get('/members', 'members');
    Route::get('/voucher', 'voucher');
    Route::post('/voucher/tambah', 'tambahVoucher');
    Route::post('/voucher/ubahaktif', 'ubahAktifVoucher');
    Route::get('/saran', 'saran');
    Route::post('/ambil-sarankomplain', 'ambilSaranKomplain');
    Route::post('/kirim-balasan', 'kirimBalasan');
    Route::get('/laporan', 'laporan');
    Route::post('/get-month', 'getMonth');
    Route::post('/cetak-laporan', 'cetakLaporan');
    Route::get('/laporanview', 'laporanview');
    Route::get('/get-service-type', 'getServiceType');
    Route::patch('/update-service-type', 'updateServiceType');
});
