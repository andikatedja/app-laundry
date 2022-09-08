<?php

namespace App\Http\Controllers;

use App\Models\ComplaintSuggestion;
use Illuminate\Http\Request;
use App\Models\PriceList;
use App\Models\ServiceType;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\UserVoucher;
use App\Models\Voucher;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    /**
     * Method to show member dashboard view
     *
     * @return View
     */
    public function index(): View
    {
        $user = Auth::user();
        $latestTransactions = Transaction::where('member_id', $user->id)
            ->orderBy('created_at', 'DESC')
            ->orderBy('status_id', 'ASC')
            ->limit(10)
            ->get();

        return view('member.index', compact('user', 'latestTransactions'));
    }

    /**
     * Method to show price lists view
     *
     * @return View
     */
    public function harga(): View
    {
        $user = Auth::user();
        $satuan = PriceList::where('category_id', 1)->get();
        $kiloan = PriceList::where('category_id', 2)->get();
        $serviceTypes = ServiceType::all();

        return view('member.price_lists', compact('user', 'satuan', 'kiloan', 'serviceTypes'));
    }

    /**
     * Method to show transactions history based on current logged on member
     *
     * @return View
     */
    public function riwayatTransaksi(): View
    {
        $user = Auth::user();
        $transactions = Transaction::where('member_id', $user->id)
            ->orderBy('created_at', 'DESC')
            ->orderBy('status_id', 'ASC')
            ->get();

        return view('member.transactions_history', compact('user', 'transactions'));
    }

    /**
     * Method to show member points view
     *
     * @return View
     */
    public function poin(): View
    {
        $user = Auth::user();
        $vouchers = Voucher::where('active_status', 1)->get();
        $memberVouchers = UserVoucher::where([
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
    public function tukarPoin(string|int $voucher_id): RedirectResponse
    {
        $user = User::where('email', '=', Auth::user()->email)->first();

        // Get the voucher data
        $voucher = Voucher::where('id', $voucher_id)->first();

        // Check if member's points are sufficient to redeem
        // If points are sufficient, then save to database
        if ($user->point >= $voucher->point_need) {
            $user_voucher = new UserVoucher([
                'voucher_id' => $voucher_id,
                'user_id' => $user->id,
                'used' => 0
            ]);
            $user_voucher->save();

            // Subtract member's point
            $user->point = $user->point - $voucher->point_need;
            $user->save();

            // Redirect to point view
            return redirect('member/poin')->with('success', 'Poin berhasil ditukar menjadi voucher!');
        } else {
            return redirect('member/poin')->with('error', 'Poin tidak mencukupi untuk menukar voucher!');
        }
    }

    /**
     * method to show complaint suggestion view
     *
     * @return View
     */
    public function saranKomplain(): View
    {
        $user = Auth::user();
        $complaintSuggestions = ComplaintSuggestion::where('user_id', $user->id)->get();

        return view('member.complaint_suggestions', compact('user', 'complaintSuggestions'));
    }

    /**
     * Method to process complaint suggestion
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function kirimSaranKomplain(Request $request): RedirectResponse
    {
        $request->validate([
            'isi_sarankomplain' => 'required'
        ]);

        $user = Auth::user();

        $complaintSuggestion = new ComplaintSuggestion([
            'body' => $request->input('isi_sarankomplain'),
            'type' => $request->input('tipe'),
            'user_id' => $user->id,
            'reply' => ''
        ]);

        $complaintSuggestion->save();

        return redirect('member/saran')->with('success', 'Saran/komplain berhasil dikirim!');
    }

    /**
     * Method to show detail transaction
     *
     * @param string|int $id
     * @return View
     */
    public function detailTransaksi(string|int $id): View
    {
        $user = Auth::user();
        $transactions = TransactionDetail::where('transaction_id', $id)->get();

        return view('member.detail', compact('user', 'transactions', 'id'));
    }
}
