<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ComplaintSuggestion;
use App\Models\Item;
use App\Models\PriceList;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\Status;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\UserVoucher;
use App\Models\Voucher;
use PDF;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Admin extends Controller
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

    /**
     * Function to show admin input transaction view
     *
     * @param Request $request
     * @return void
     */
    public function inputTransaksi(Request $request)
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
     * Method to add new transaction to session
     *
     * @param Request $request
     * @return void
     */
    public function tambahTransaksi(Request $request)
    {
        $itemId = $request->input('item');
        $serviceId = $request->input('service');
        $categoryId = $request->input('category');
        $memberId = $request->input('member-id');
        $quantity = $request->input('quantity');

        // Check if price list exist in database
        if (!PriceList::where([
            'item_id' => $itemId,
            'category_id' => $categoryId,
            'service_id' => $serviceId
        ])->exists()) {
            return redirect('admin/input-transaksi')->with('error', 'Harga tidak ditemukan!');
        }

        // Check if member exist
        if ($memberId != null && !User::where('id', $memberId)->where('role', 2)->exists()) {
            return redirect('admin/input-transaksi')->with('error', 'Member tidak ditemukan!');
        }

        // Get price list's price from database
        $price = PriceList::where([
            'item_id' => $itemId,
            'category_id' => $categoryId,
            'service_id' => $serviceId
        ])->first()->price;

        // Calculate sub total
        $subTotal = $price * $quantity;

        // Get item name, service name, and category name based on id
        $itemName = Item::where('id', $itemId)->first()->name;
        $serviceName = Service::where('id', $serviceId)->first()->name;
        $categoryName = Category::where('id', $categoryId)->first()->name;

        // make new transaction row to store in session
        $rowId = md5($memberId . serialize($itemId) . serialize($serviceId) . serialize($categoryId));
        $data = [
            $rowId => [
                'itemId' => $itemId,
                'itemName' => $itemName,
                'categoryId' => $categoryId,
                'categoryName' => $categoryName,
                'serviceId' => $serviceId,
                'serviceName' => $serviceName,
                'quantity' => $quantity,
                'subTotal' => $subTotal,
                'rowId' => $rowId
            ]
        ];

        // Check if there is no transaction session, create new session
        if (!$request->session()->has('transaction') && !$request->session()->has('memberIdTransaction')) {
            $request->session()->put('transaction', $data);
            $request->session()->put('memberIdTransaction', $memberId);
        } else {
            $exist = 0;
            $sessionTransaction = $request->session()->get('transaction');

            // Check if there is a same transaction. If exist, just increment the quantity and subtotal
            foreach ($sessionTransaction as &$transaction) {
                if ($transaction['itemId'] == $itemId && $transaction['categoryId'] == $categoryId && $transaction['serviceId'] == $serviceId) {
                    $transaction['quantity'] += $quantity;
                    $transaction['subTotal'] += $subTotal;
                    $exist++;
                }
            }

            // check if there is no same transaction, then insert new transaction to current transaction session
            if ($exist == 0) {
                $newSessionTransaction = array_merge_recursive($sessionTransaction, $data);
                $request->session()->put('transaction', $newSessionTransaction);
            } else {
                $request->session()->put('transaction', $sessionTransaction);
            }
        }

        return redirect('admin/input-transaksi');
    }

    /**
     * Method for delete current transaction in session
     *
     * @param mixed $row_id
     * @param Request $request
     * @return void
     */
    public function hapusTransaksi(mixed $row_id, Request $request)
    {
        $sessionTransaction = $request->session()->get('transaction');
        unset($sessionTransaction[$row_id]);

        // Check if after unset, the transaction session is empty ([]), then destroy all transaction session
        if ($sessionTransaction == []) {
            $request->session()->forget('transaction');
            $request->session()->forget('memberIdTransaction');
            return redirect('admin/input-transaksi');
        } else {
            $request->session()->put('transaction', $sessionTransaction);
        }

        return redirect('admin/input-transaksi');
    }

    /**
     * Fungsi untuk menyimpan transaksi dari sesi transaksi
     */
    public function simpanTransaksi(Request $request)
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
     * Fungsi untuk mencetak transaksi
     */
    public function cetakTransaksi(mixed $id)
    {
        $transaction = Transaction::where('id', $id)->first();

        return view('admin.print_transaction', compact('id', 'transaction'));
    }

    /**
     * Fungsi untuk menghapus sesi transaksi
     */
    public function hapusSessTransaksi(Request $request)
    {
        $request->session()->forget('transaction');
        $request->session()->forget('memberIdTransaction');

        return redirect('admin/input-transaksi');
    }

    /**
     * Fungsi untuk menampilkan halaman riwayat transaksi
     */
    public function riwayatTransaksi(Request $request)
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
     * Fungsi untuk mengambil detail transaksi dari ajax
     */
    public function ambilDetailTransaksi(Request $request)
    {
        $transactionDetails = TransactionDetail::with(['transaction', 'transaction.service_type', 'price_list', 'price_list.item', 'price_list.service', 'price_list.category'])->where('transaction_id', $request->input('id_transaksi'))->get();

        return response()->json($transactionDetails);
    }

    /**
     * Fungsi untuk mengubah status transaksi dari sedang dikerjakan menjadi selesai
     */
    public function ubahStatusTransaksi(Request $request)
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

    /**
     * Fungsi untuk menampilkan halaman daftar harga
     */
    public function harga()
    {
        $user = Auth::user();
        $satuan = PriceList::where('category_id', 1)->get();
        $kiloan = PriceList::where('category_id', 2)->get();
        $items = Item::all();
        $services = Service::all();
        $categories = Category::all();
        $serviceTypes = ServiceType::all();

        return view('admin.price_lists', compact('user', 'satuan', 'kiloan', 'items', 'services', 'categories', 'serviceTypes'));
    }

    /**
     * Fungsi untuk menambah harga baru
     */
    public function tambahHarga(Request $request)
    {
        $request->validate([
            'harga' => 'required'
        ]);

        if (PriceList::where([
            'item_id' => $request->input('barang'),
            'category_id' => $request->input('kategori'),
            'service_id' => $request->input('servis')
        ])->exists()) {
            return redirect('admin/harga')->with('error', 'Harga tidak dapat ditambah karena sudah tersedia!');
        }

        $priceList = new PriceList([
            'item_id' => $request->input('barang'),
            'category_id' => $request->input('kategori'),
            'service_id' => $request->input('servis'),
            'price' => $request->input('harga')
        ]);
        $priceList->save();

        return redirect('admin/harga')->with('success', 'Harga berhasil ditambahkan!');
    }

    /**
     * Fungsi untuk mengambil harga untuk ajax
     */
    public function ambilHarga(Request $request)
    {
        $priceId = $request->input('id_harga');
        $price = PriceList::where('id', $priceId)->first();

        return response()->json($price);
    }

    /**
     * Fungsi untuk mengubah harga
     */
    public function ubahHarga(Request $request)
    {
        $priceId = $request->input('id_harga');
        $priceList = PriceList::where('id', $priceId)->first();
        $priceList->price = $request->input('harga');
        $priceList->save();

        return redirect('admin/harga')->with('success', 'Harga berhasil diubah!');
    }

    /**
     * Fungsi untuk menambah barang baru
     */
    public function tambahBarang(Request $request)
    {
        $item = new Item([
            'name' => ucfirst($request->input('barang'))
        ]);
        $item->save();

        return redirect('admin/harga')->with('success', 'Barang baru berhasil ditambah!');
    }

    /**
     * Fungsi untuk menambah servis baru
     */
    public function tambahServis(Request $request)
    {
        $service = new Service([
            'name' => ucfirst($request->input('servis'))
        ]);
        $service->save();

        return redirect('admin/harga')->with('success', 'Servis baru berhasil ditambah!');
    }

    /**
     * Fungsi untuk mengambil biaya service type
     */
    public function getServiceType(Request $request)
    {
        $serviceTypeId = $request->input('id_cost');
        $serviceType = ServiceType::where('id', $serviceTypeId)->first();

        return response()->json($serviceType);
    }

    /**
     * Fungsi untuk mengubah service type
     */
    public function updateServiceType(Request $request)
    {
        $serviceTypeId = $request->input('id_cost');
        $serviceType = ServiceType::where('id', $serviceTypeId)->first();
        $serviceType->cost = $request->input('cost');
        $serviceType->save();

        return redirect('admin/harga')->with('success', 'Biaya service berhasil diubah!');
    }

    /**
     * Fungsi untuk menampilkan halaman daftar member
     */
    public function members()
    {
        $user = Auth::user();
        $members = User::where('role', 2)->get();

        return view('admin.members', compact('user', 'members'));
    }

    /**
     * Fungsi untuk menampilkan halaman voucher
     */
    public function voucher()
    {
        $user = Auth::user();
        $vouchers = Voucher::all();

        return view('admin.voucher', compact('user', 'vouchers'));
    }

    /**
     * Fungsi untuk menambahkan voucher baru
     */
    public function tambahVoucher(Request $request)
    {
        // Cek apakah potongan ada yang sama di database
        if (Voucher::where('discount_value', $request->input('potongan'))->exists()) {
            return redirect('admin/voucher')->with('error', 'Voucher potongan ' . $request->input('potongan') . ' sudah ada');
        }

        // Masukkan potongan ke dalam tabel vouchers
        $voucher = new Voucher([
            'name' => 'Potongan ' . number_format($request->input('potongan'), 0, ',', '.'),
            'discount_value' => $request->input('potongan'),
            'point_need' => $request->input('poin'),
            'description' => 'Mendapatkan potongan harga ' . number_format($request->input('potongan'), 0, ',', '.') . ' dari total transaksi',
            'active_status' => 1
        ]);
        $voucher->save();

        return redirect('admin/voucher')->with('success', 'Voucher baru berhasil ditambah!');
    }

    /**
     * Fungsi untuk mengubah status aktif voucher
     */
    public function ubahAktifVoucher(Request $request)
    {
        // Cek apakah status aktif atau tidak aktif
        // 1 artinya aktif, 0 artinya tidak aktif
        $voucher = Voucher::where('id', $request->input('id'))->first();

        // Jika 1 maka ubah ke 0
        if ($voucher->active_status == 1) {
            $voucher->active_status = 0;
            $voucher->save();
        } else {
            // Ubah ke 1
            $voucher->active_status = 1;
            $voucher->save();
        }
    }

    /**
     * Fungsi untuk menampilkan halaman saran komplain
     */
    public function saran()
    {
        $user = Auth::user();
        $suggestions = ComplaintSuggestion::where([
            'type' => 1,
            'reply' => ''
        ])->get();
        $complaints = ComplaintSuggestion::where([
            'type' => 2,
            'reply' => ''
        ])->get();
        $count = ComplaintSuggestion::where('reply', '')->count();
        return view('admin.complaint_suggestion', compact('user', 'suggestions', 'complaints', 'count'));
    }

    /**
     * Fungsi untuk mengambil isi saran komplain melalui ajax
     */
    public function ambilSaranKomplain(Request $request)
    {
        $complaintSuggestion = ComplaintSuggestion::where('id', $request->input('id'))->first();

        return response()->json($complaintSuggestion);
    }

    /**
     * Fungsi untuk mengirim balasan dari saran komplain
     */
    public function kirimBalasan(Request $request)
    {
        $complaintSuggestion = ComplaintSuggestion::where('id', $request->input('id'))->first();
        $complaintSuggestion->reply = $request->input('balasan');
        $complaintSuggestion->save();
    }

    /**
     * Fungsi untuk menampilkan halaman laporan keuangan
     */
    public function laporan()
    {
        $user = Auth::user();
        $years = Transaction::selectRaw('YEAR(created_at) as Tahun')->distinct()->get();
        // $months = Transaction::selectRaw('MONTH(created_at) as Bulan')->distinct()->get();

        return view('admin.report', compact('user', 'years'));
    }

    /**
     * Fungsi untuk mendapatkan bulan berdasarkan tahun transaksi
     */
    public function getMonth(Request $request)
    {
        $year = $request->input('tahun');
        $month = Transaction::whereYear('created_at', $year)->selectRaw('MONTH(created_at) as Bulan')->distinct()->get();

        return response()->json($month);
    }

    /**
     * Fungsi untuk mencetak laporan dengan konversi ke pdf
     */
    public function cetakLaporan(Request $request)
    {
        $monthInput = $request->input('bulan');
        $yearInput = $request->input('tahun');
        $dateObj = DateTime::createFromFormat('!m', $monthInput);
        $month = $dateObj->format('F');
        $revenue = Transaction::whereMonth('created_at', $monthInput)
            ->whereYear('created_at', $yearInput)->sum('total');
        $transactionsCount = Transaction::whereMonth('created_at', $monthInput)
            ->whereYear('created_at', $yearInput)->count();
        $pdf = PDF::loadview('admin.report_pdf', compact('monthInput', 'yearInput', 'revenue', 'transactionsCount'));
        // return $pdf->download('laporan-keuangan-' . $bulan . '-' . $tahun . '.pdf');

        return $pdf->stream();
    }

    public function laporanview(Request $request)
    {
        $monthInput = 8;
        $year = 2020;
        $dateObj = DateTime::createFromFormat('!m', $monthInput);
        $month = $dateObj->format('F');
        $revenue = Transaction::whereMonth('created_at', $monthInput)
            ->whereYear('created_at', $year)->sum('total');
        $transactionsCount = Transaction::whereMonth('created_at', $monthInput)
            ->whereYear('created_at', $year)->count();
        return view('admin.laporan_pdf', compact('month', 'year', 'revenue', 'transactionsCount'));
    }
}
