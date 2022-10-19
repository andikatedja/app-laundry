<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\PriceList;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\Status;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\UserVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Fungsi untuk menampilkan halaman riwayat transaksi
     */
    public function index(Request $request)
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        $monthQuery = $request->query('month');
        $yearQuery = $request->query('year');
        if ($monthQuery && $yearQuery) {
            $currentMonth = $monthQuery;
            $currentYear = $yearQuery;
        }

        $user = Auth::user();

        $ongoingTransactions = Transaction::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->where('service_type_id', 1)
            ->where('finish_date', null)
            ->orderBy('created_at', 'DESC')
            ->get();
        $ongoingPriorityTransactions = Transaction::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->where('service_type_id', 2)
            ->where('finish_date', null)
            ->orderBy('created_at', 'DESC')
            ->get();
        $finishedTransactions = Transaction::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->where('finish_date', '!=', null)
            ->orderBy('created_at', 'DESC')
            ->get();
        $status = Status::all();
        $years = Transaction::selectRaw('YEAR(created_at) as Tahun')->distinct()->get();

        return view('admin.transactions_history', compact('user', 'status', 'years', 'currentYear', 'currentMonth', 'ongoingTransactions', 'ongoingPriorityTransactions', 'finishedTransactions'));
    }

    /**
     * Function to show admin input transaction view
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $items = Item::all();
        $categories = Category::all();
        $services = Service::all();
        $serviceTypes = ServiceType::all();

        // Check if there is an active transaction in session
        if ($request->session()->has('transaction') && $request->session()->has('memberIdTransaction')) {
            $sessionTransaction = $request->session()->get('transaction');

            $memberIdSessionTransaction = $request->session()->get('memberIdTransaction');

            // Get user's voucher
            $vouchers = UserVoucher::where([
                'user_id' => $memberIdSessionTransaction,
                'used' => 0
            ])->get();

            // Sum total price
            $totalPrice = 0;
            foreach ($sessionTransaction as &$transaction) {
                $totalPrice += $transaction['subTotal'];
            }

            return view('admin.transaction_input', compact('user', 'items', 'categories', 'services', 'serviceTypes', 'sessionTransaction', 'memberIdSessionTransaction', 'totalPrice', 'vouchers'));
        }

        return view('admin.transaction_input', compact('user', 'items', 'categories', 'services', 'serviceTypes'));
    }

    /**
     * Fungsi untuk menyimpan transaksi dari sesi transaksi
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        $memberId = $request->session()->get('memberIdTransaction');
        $adminId = Auth::user()->id;
        $sessionTransaction = $request->session()->get('transaction');

        // Hitung total harga
        $totalPrice = 0;
        foreach ($sessionTransaction as &$trs) {
            $totalPrice += $trs['subTotal'];
        }
        $discount = 0;

        //Cek apakah ada voucher yang digunakan
        if ($request->input('voucher') != 0) {
            // Ambil banyak potongan dari database

            $userVoucher = UserVoucher::where('id', $request->input('voucher'))->first();
            $discount = $userVoucher->voucher->discount_value;

            // Kurangi harga dengan potongan
            $totalPrice -= $discount;
            if ($totalPrice < 0) {
                $totalPrice = 0;
            }

            // Ganti status used pada tabel users_vouchers
            $userVoucher->used = 1;
            $userVoucher->save();
        }

        // Cek apakah menggunakan service type non reguler
        if ($request->input('service-type') != 0) {
            $serviceTypeCost = ServiceType::where('id', $request->input('service-type'))->first();
            $cost = $serviceTypeCost->cost;
            // Tambahkan harga dengan cost
            $totalPrice += $cost;
        }

        // Check if payment < total
        if ($request->input('payment-amount') < $totalPrice) {
            return redirect('admin/input-transaksi')->with('error', 'Pembayaran kurang');
        }

        $transaction = new Transaction([
            'status_id' => 1,
            'member_id' => $memberId,
            'admin_id' => $adminId,
            'finish_date' => null,
            'discount' => $discount,
            'total' => $totalPrice,
            'service_type_id' => $request->input('service-type'),
            'service_cost' => $cost,
            'payment_amount' => $request->input('payment-amount'),
        ]);
        $transaction->save();

        foreach ($sessionTransaction as &$trs) {
            // dd($trs);
            $price = PriceList::where([
                'item_id' => $trs['itemId'],
                'category_id' => $trs['categoryId'],
                'service_id' => $trs['serviceId']
            ])->first();

            $transaction_detail = new TransactionDetail([
                'transaction_id' => $transaction->id,
                'price_list_id' => $price->id,
                'quantity' => $trs['quantity'],
                'price' => $price->price,
                'sub_total' => $trs['subTotal']
            ]);
            $transaction_detail->save();
        }

        $user = User::where('id', $memberId)->first();
        $user->point = $user->point + 1;
        $user->save();

        $request->session()->forget('transaction');
        $request->session()->forget('memberIdTransaction');

        DB::commit();

        return redirect('admin/input-transaksi')->with('success', 'Transaksi berhasil disimpan')->with('id_trs', $transaction->id);
    }

    /**
     * Fungsi untuk mengambil detail transaksi dari ajax
     */
    public function show(Transaction $transaction)
    {
        $transaction = $transaction->with(['transaction_detail', 'transaction.service_type', 'price_list', 'price_list.item', 'price_list.service', 'price_list.category'])->get();

        return response()->json($transaction);
    }

    /**
     * Fungsi untuk mengubah status transaksi dari sedang dikerjakan menjadi selesai
     */
    public function update(Transaction $transaction, Request $request)
    {
        $currentDate = null;
        // Jika status 3 maka artinya sudah selesai, set tgl menjadi tgl selesai
        if ($request->input('val') == 3) {
            $currentDate = date('Y-m-d H:i:s');
        }

        $transaction = Transaction::where('id', $request->input('id_transaksi'))->first();
        $transaction->status_id = $request->input('val');
        $transaction->finish_date = $currentDate;
        $transaction->save();
    }
}