<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Function to show admin dashboard view
     *
     * @return void
     */
    public function index()
    {
        $user = Auth::user();

        $recentTransactions = Transaction::where('finish_date', null)
            ->where('service_type_id', 1)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $membersCount = User::where('role', 2)->count();

        $transactionsCount = Transaction::count();

        $priorityTransactions = Transaction::where('finish_date', null)
            ->where('service_type_id', 2)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.index', compact('user', 'recentTransactions', 'membersCount', 'transactionsCount', 'priorityTransactions'));
    }
}
