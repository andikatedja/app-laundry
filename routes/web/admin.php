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
    'prefix' => 'transactions',
    'as' => 'transactions.',
], function () {
    Route::get('/create', [TransactionController::class, 'create'])->name('create');
    Route::get('/', [TransactionController::class, 'index'])->name('index');
    Route::post('/', [TransactionController::class, 'store'])->name('store');
    Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
    Route::patch('/{transaction}', [TransactionController::class, 'update'])->name('update');

    Route::post('/session', [TransactionSessionController::class, 'store'])->name('session.store');
    Route::get('/session/{rowId}', [TransactionSessionController::class, 'destroy'])->name('session.destroy');

    Route::get('/print/{transaction}', [PrintTransactionController::class, 'index'])->name('print.index');
});

Route::group([
    'prefix' => 'price-lists',
    'as' => 'price-lists.',
], function () {
    Route::get('/', [PriceListController::class, 'index'])->name('index');
    Route::post('/', [PriceListController::class, 'store'])->name('store');
    Route::get('/{priceList}', [PriceListController::class, 'show'])->name('show');
    Route::patch('/{priceList}', [PriceListController::class, 'update'])->name('update');
});

Route::post('/items', [ItemController::class, 'store'])->name('items.store');

Route::post('/services', [ServiceController::class, 'store'])->name('services.store');

Route::get('/members', [MemberController::class, 'index'])->name('members.index');

Route::group([
    'prefix' => 'vouchers',
    'as' => 'vouchers.',
], function () {
    Route::get('/', [VoucherController::class, 'index'])->name('index');
    Route::post('/', [VoucherController::class, 'store'])->name('store');
    Route::patch('/{voucher}', [VoucherController::class, 'update'])->name('update');
});

Route::group([
    'prefix' => 'complaint-suggestions',
    'as' => 'complaint-suggestions.',
], function () {
    Route::get('/', [ComplaintSuggestionController::class, 'index'])->name('index');
    Route::get('/{complaintSuggestion}', [ComplaintSuggestionController::class, 'show'])->name('show');
    Route::patch('/{complaintSuggestion}', [ComplaintSuggestionController::class, 'update'])->name('update');
});

Route::group([
    'prefix' => 'reports',
    'as' => 'reports.',
], function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::post('/print', [ReportController::class, 'print'])->name('print');
    Route::post('/get-month', [ReportController::class, 'getMonth'])->name('get-month');
});

// Route::get('/laporanview', 'laporanview');

Route::group([
    'prefix' => 'service-types',
    'as' => 'service-types.',
], function () {
    Route::get('/{serviceType}', [ServiceTypeController::class, 'show'])->name('show');
    Route::patch('/{serviceType}', [ServiceTypeController::class, 'update'])->name('update');
});
