<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PrintTransactionController extends Controller
{
    /**
     * Print transaction
     *
     * @param  \App\Models\Transaction $transaction
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Transaction $transaction): View
    {
        return view('admin.print_transaction', compact('transaction'));
    }
}
