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
     * @return View
     */
    public function point(): View
    {
        $user = Auth::user();
        $vouchers = Voucher::where('active_status', 1)->get();
        $memberVouchers = UserVoucher::with('voucher')->where([
            'user_id' => $user->id,
            'used' => 0
        ])->get();

        return view('member.point', compact('user', 'vouchers', 'memberVouchers'));
    }

    /**
     * Method to process point redemption
     *
     * @param string|int $voucher_id
     * @return RedirectResponse
     */
    public function redeemVoucher(string|int $voucher): RedirectResponse
    {
        $user = User::where('email', '=', Auth::user()->email)->first();

        // Get the voucher data
        $voucher = Voucher::where('id', $voucher)->first();

        // Check if member's points are sufficient to redeem
        // If points are sufficient, then save to database
        if ($user->point >= $voucher->point_need) {
            $user_voucher = new UserVoucher([
                'voucher_id' => $voucher,
                'user_id' => $user->id,
                'used' => 0
            ]);
            $user_voucher->save();

            // Subtract member's point
            $user->point = $user->point - $voucher->point_need;
            $user->save();

            // Redirect to point view
            return redirect('member/point')->with('success', 'Poin berhasil ditukar menjadi voucher!');
        } else {
            return redirect('member/point')->with('error', 'Poin tidak mencukupi untuk menukar voucher!');
        }
    }
}
