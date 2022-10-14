<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\UserVoucher;
use App\Models\Voucher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    /**
     * Method to process point redemption
     *
     * @param string|int $voucher_id
     * @return RedirectResponse
     */
    public function store(string|int $voucher): RedirectResponse
    {
        $user = Auth::user();

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
