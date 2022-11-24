<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserVoucher;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PointController extends Controller
{
    /**
     * Method to show member points view
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $vouchers = Voucher::where('active_status', 1)->get();
        $memberVouchers = UserVoucher::with('voucher')->where([
            'user_id' => $user->id,
            'used' => 0,
        ])->get();

        return view('member.point', compact('user', 'vouchers', 'memberVouchers'));
    }
}
