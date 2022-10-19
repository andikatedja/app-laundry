<?php

use App\Http\Controllers\Admin\ComplaintSuggestionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\PriceListController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\Transaction\PrintTransactionController;
use App\Http\Controllers\Admin\Transaction\TransactionController;
use App\Http\Controllers\Admin\Transaction\TransactionSessionController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\AdminController;

Route::group([
    'middleware' => ['language', 'islogin'],
    'controller' => AdminController::class
], function () {
    Route::get('/', [DashboardController::class, 'index']);

    Route::get('/transactions/create', [TransactionController::class, 'create']);
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show']);
    Route::patch('/transactions/{transaction}', [TransactionController::class, 'update']);

    Route::post('/transactions/session', [TransactionSessionController::class, 'store']);
    Route::get('/transactions/session/{rowId}', [TransactionSessionController::class, 'destroy']);

    Route::get('/transactions/print/{transaction}', [PrintTransactionController::class, 'index']);

    Route::get('/price-lists', [PriceListController::class, 'index']);
    Route::post('/price-lists', [PriceListController::class, 'store']);
    Route::get('/price-lists/{priceList}', [PriceListController::class, 'show']);
    Route::patch('/price-lists/{priceList}', [PriceListController::class, 'update']);

    Route::post('/items', [ItemController::class, 'store']);

    Route::post('/services', [ServiceController::class, 'store']);

    Route::get('/members', [MemberController::class, 'index']);

    Route::get('/vouchers', [VoucherController::class, 'index']);
    Route::post('/vouchers', [VoucherController::class, 'store']);
    Route::patch('/vouchers/{voucher}', [VoucherController::class, 'update']);

    Route::get('/complaint-suggestions', [ComplaintSuggestionController::class, 'index']);
    Route::get('/complaint-suggestions/{complaintSuggestion}', [ComplaintSuggestionController::class, 'show']);
    Route::post('/complaint-suggestions/{complaintSuggestion}', [ComplaintSuggestionController::class, 'update']);

    Route::get('/reports', [ReportController::class, 'index']);
    Route::post('/reports/print', [ReportController::class, 'print']);

    Route::post('/get-month', 'getMonth');
    Route::get('/laporanview', 'laporanview');
    Route::get('/get-service-type', 'getServiceType');
    Route::patch('/update-service-type', 'updateServiceType');
});
