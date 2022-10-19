<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PrintTransactionController extends Controller
{
    /**
     * Fungsi untuk mencetak transaksi
     */
    public function index(Transaction $transaction)
    {
        return view('admin.print_transaction', compact('id', 'transaction'));
    }
}
