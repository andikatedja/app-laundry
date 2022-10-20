<?php

use App\Http\Controllers\Admin\ComplaintSuggestionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\PriceListController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ServiceTypeController;
use App\Http\Controllers\Admin\Transaction\PrintTransactionController;
use App\Http\Controllers\Admin\Transaction\TransactionController;
use App\Http\Controllers\Admin\Transaction\TransactionSessionController;
use App\Http\Controllers\Admin\VoucherController;

Route::get('/', [DashboardController::class, 'index'])->name('index');

Route::group([
    'as' => 'transactions.',
], function () {
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('create');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('store');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('show');
    Route::patch('/transactions/{transaction}', [TransactionController::class, 'update'])->name('update');

    Route::post('/transactions/session', [TransactionSessionController::class, 'store'])->name('session.store');
    Route::get('/transactions/session/{rowId}', [TransactionSessionController::class, 'destroy'])->name('session.destroy');

    Route::get('/transactions/print/{transaction}', [PrintTransactionController::class, 'index'])->name('print.index');
});

Route::group([
    'as' => 'price-lists.',
], function () {
    Route::get('/price-lists', [PriceListController::class, 'index'])->name('index');
    Route::post('/price-lists', [PriceListController::class, 'store'])->name('store');
    Route::get('/price-lists/{priceList}', [PriceListController::class, 'show'])->name('show');
    Route::patch('/price-lists/{priceList}', [PriceListController::class, 'update'])->name('update');
});

Route::post('/items', [ItemController::class, 'store'])->name('items.store');

Route::post('/services', [ServiceController::class, 'store'])->name('services.store');

Route::get('/members', [MemberController::class, 'index'])->name('members.index');

Route::group([
    'as' => 'vouchers.',
], function () {
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('index');
    Route::post('/vouchers', [VoucherController::class, 'store'])->name('store');
    Route::patch('/vouchers/{voucher}', [VoucherController::class, 'update'])->name('update');
});

Route::group([
    'as' => 'complaint-suggestions.',
], function () {
    Route::get('/complaint-suggestions', [ComplaintSuggestionController::class, 'index'])->name('index');
    Route::get('/complaint-suggestions/{complaintSuggestion}', [ComplaintSuggestionController::class, 'show'])->name('show');
    Route::post('/complaint-suggestions/{complaintSuggestion}', [ComplaintSuggestionController::class, 'update'])->name('update');
});

Route::group([
    'as' => 'reports.',
], function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('index');
    Route::post('/reports/print', [ReportController::class, 'print'])->name('print');
    Route::post('/reports/get-month', [ReportController::class, 'getMonth'])->name('get-month');
});

// Route::get('/laporanview', 'laporanview');

Route::group([
    'as' => 'service-types.',
], function () {
    Route::get('/service-types/{serviceType}', [ServiceTypeController::class, 'show'])->name('show');
    Route::patch('/service-types/{serviceType}', [ServiceTypeController::class, 'update'])->name('update');
});
