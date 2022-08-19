<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\ComplaintSuggestion;
use App\Item;
use App\PriceList;
use App\Service;
use App\ServiceType;
use App\Status;
use App\Transaction;
use App\TransactionDetail;
use App\User;
use App\UserVoucher;
use App\Voucher;
use PDF;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Admin extends Controller
{
    /**
     * Fungsi index untuk menampilkan halaman dashboard admin
     */
    public function index()
    {
        $user = Auth::user();
        $transaksi_terbaru = Transaction::where('finish_date', null)
            ->where('service_type_id', 1)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
        $banyak_member = User::where('role', 2)->count();
        $banyak_transaksi = Transaction::count();
        $priority_service = Transaction::where('finish_date', null)
            ->where('service_type_id', 2)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
        return view('admin.index', compact('user', 'transaksi_terbaru', 'banyak_member', 'banyak_transaksi', 'priority_service'));
    }

    /**
     * Fungsi untuk menampilkan halaman input transaksi
     */
    public function inputTransaksi(Request $request)
    {
        $user = Auth::user();
        $barang = Item::all();
        $kategori = Category::all();
        $servis = Service::all();
        $serviceType = ServiceType::all();

        // Mengecek apakah ada sesi transaksi atau tidak
        if ($request->session()->has('transaksi') && $request->session()->has('id_member_transaksi')) {
            $transaksi = $request->session()->get('transaksi');
            $id_member_transaksi = $request->session()->get('id_member_transaksi');
            // Mengambil voucher yang dimiliki member
            $vouchers = UserVoucher::where([
                'user_id' => $id_member_transaksi,
                'used' => 0
            ])->get();

            // Hitung total harga
            $total_harga = 0;
            foreach ($transaksi as &$t) {
                $total_harga += $t['harga'];
            }
            return view('admin.input_transaksi', compact('user', 'barang', 'kategori', 'servis', 'serviceType', 'transaksi', 'id_member_transaksi', 'total_harga', 'vouchers'));
        }
        return view('admin.input_transaksi', compact('user', 'barang', 'kategori', 'servis', 'serviceType'));
    }

    /**
     * Fungsi untuk menambahkan transaksi baru ke dalam sesi (session) transaksi
     */
    public function tambahTransaksi(Request $request)
    {
        $id_barang = $request->input('barang');
        $id_servis = $request->input('servis');
        $id_kategori = $request->input('kategori');
        $id_member = $request->input('id_member');
        $banyak = $request->input('banyak');

        // Cek apakah harga tertera pada database
        if (!PriceList::where([
            'item_id' => $id_barang,
            'category_id' => $id_kategori,
            'service_id' => $id_servis
        ])->exists()) {
            return redirect('admin/input-transaksi')->with('error', 'Harga tidak ditemukan!');
        }

        //Cek member
        if ($id_member != null && !User::where('id', $id_member)->where('role', 2)->exists()) {
            return redirect('admin/input-transaksi')->with('error', 'Member tidak ditemukan!');
        }

        // Ambil harga dari database
        $dbharga = PriceList::where([
            'item_id' => $id_barang,
            'category_id' => $id_kategori,
            'service_id' => $id_servis
        ])->pluck('price')[0];

        // Hitung subtotal
        $harga = $dbharga * $banyak;

        // Ambil nama barang, servis, kategori berdasarkan id
        $nama_barang = Item::where('id', $id_barang)->pluck('name')[0];
        $nama_servis = Service::where('id', $id_servis)->pluck('name')[0];
        $nama_kategori = Category::where('id', $id_kategori)->pluck('name')[0];

        // Membuat row baru untuk disimpan dalam session
        $row_id = md5($id_member . serialize($id_barang) . serialize($id_servis) . serialize($id_kategori));

        $data = [
            $row_id => [
                'id_barang' => $id_barang,
                'nama_barang' => $nama_barang,
                'id_kategori' => $id_kategori,
                'nama_kategori' => $nama_kategori,
                'id_servis' => $id_servis,
                'nama_servis' => $nama_servis,
                'banyak' => $banyak,
                'harga' => $harga,
                'row_id' => $row_id
            ]
        ];

        // Jika tidak ada sesi transaksi, buat baru transaksi
        if (!$request->session()->has('transaksi') && !$request->session()->has('id_member_transaksi')) {
            $request->session()->put('transaksi', $data);
            $request->session()->put('id_member_transaksi', $id_member);
        } else {
            $exist = 0;
            $transaksi = $request->session()->get('transaksi');

            // Mengecek apakah ada input transaksi yang sama, jika ada maka tambahkan banyak dan harga dari sesi transaksi
            foreach ($transaksi as &$t) {
                if ($t['id_barang'] == $id_barang && $t['id_kategori'] == $id_kategori && $t['id_servis'] == $id_servis) {
                    $t['banyak'] += $banyak;
                    $t['harga'] += $harga;
                    $exist++;
                }
            }

            // Cek jika tidak ada input transaksi yang sama, jika tidak ada maka tambahkan data baru ke sesi transaksi
            if ($exist == 0) {
                $newtransaksi = array_merge_recursive($transaksi, $data);
                $request->session()->put('transaksi', $newtransaksi);
            } else {
                $request->session()->put('transaksi', $transaksi);
            }
        }

        return redirect('admin/input-transaksi');
    }

    /**
     * Fungsi untuk menghapus transaksi dari sesi transaksi, diakses dari menekan tombol hapus
     */
    public function hapusTransaksi($row_id, Request $request)
    {
        $newtransaksi = $request->session()->get('transaksi');
        unset($newtransaksi[$row_id]);

        // Cek jika setelah unset transaksi menjadi kosong [] maka hapus semua sesi yang berhubungan dengan transaksi
        if ($newtransaksi == []) {
            $request->session()->forget('transaksi');
            $request->session()->forget('id_member_transaksi');
            return redirect('admin/input-transaksi');
        } else {
            $request->session()->put('transaksi', $newtransaksi);
        }

        return redirect('admin/input-transaksi');
    }

    /**
     * Fungsi untuk menyimpan transaksi dari sesi transaksi
     */
    public function simpanTransaksi(Request $request)
    {
        DB::beginTransaction();
        $id_member = $request->session()->get('id_member_transaksi');
        // Ambil id admin yang sedang login
        $id_admin = Auth::user()->id;
        $transaksi = $request->session()->get('transaksi');
        // Hitung total harga
        $total_harga = 0;
        foreach ($transaksi as &$t) {
            $total_harga += $t['harga'];
        }
        $potongan = 0;

        //Cek apakah ada voucher yang digunakan
        if ($request->input('voucher') != 0) {
            // Ambil banyak potongan dari database

            $user_voucher = UserVoucher::where('id', $request->input('voucher'))->first();
            $potongan = $user_voucher->voucher->discount_value;
            // Kurangi harga dengan potongan
            $total_harga -= $potongan;
            if ($total_harga < 0) {
                $total_harga = 0;
            }

            // Ganti status used pada tabel users_vouchers
            $user_voucher->used = 1;
            $user_voucher->save();
        }

        // Cek apakah menggunakan service type non reguler
        if ($request->input('service-type') != 0) {
            $serviceTypeCost = ServiceType::where('id', $request->input('service-type'))->first();
            $cost = $serviceTypeCost->cost;
            // Tambahkan harga dengan cost
            $total_harga += $cost;
        }

        // Check if payment < total
        if ($request->input('payment-amount') < $total_harga) {
            return redirect('admin/input-transaksi')->with('error', 'Pembayaran kurang');
        }

        $transaction = new Transaction([
            'status_id' => 1,
            'member_id' => $id_member,
            'admin_id' => $id_admin,
            'finish_date' => null,
            'discount' => $potongan,
            'total' => $total_harga,
            'service_type_id' => $request->input('service-type'),
            'service_cost' => $cost,
            'payment_amount' => $request->input('payment-amount'),
        ]);
        $transaction->save();

        foreach ($transaksi as &$t) {
            $harga = PriceList::where([
                'item_id' => $t['id_barang'],
                'category_id' => $t['id_kategori'],
                'service_id' => $t['id_servis']
            ])->first();

            $transaction_detail = new TransactionDetail([
                'transaction_id' => $transaction->id,
                'price_list_id' => $harga->id,
                'quantity' => $t['banyak'],
                'price' => $harga->price,
                'sub_total' => $t['harga']
            ]);
            $transaction_detail->save();
        }

        $user = User::where('id', $id_member)->first();
        $user->point = $user->point + 1;
        $user->save();

        $request->session()->forget('transaksi');
        $request->session()->forget('id_member_transaksi');

        DB::commit();
        return redirect('admin/input-transaksi')->with('success', 'Transaksi berhasil disimpan')->with('id_trs', $transaction->id);
    }

    /**
     * Fungsi untuk mencetak transaksi
     */
    public function cetakTransaksi($id)
    {
        $dataTransaksi = Transaction::where('id', $id)->first();
        return view('admin.cetak_transaksi', compact('id', 'dataTransaksi'));
    }

    /**
     * Fungsi untuk menghapus sesi transaksi
     */
    public function hapusSessTransaksi(Request $request)
    {
        $request->session()->forget('transaksi');
        $request->session()->forget('id_member_transaksi');
        return redirect('admin/input-transaksi');
    }

    /**
     * Fungsi untuk menampilkan halaman riwayat transaksi
     */
    public function riwayatTransaksi(Request $request)
    {
        $month = date('m');
        $year = date('Y');
        $monthQuery = $request->query('month');
        $yearQuery = $request->query('year');
        if ($monthQuery && $yearQuery) {
            $month = $monthQuery;
            $year = $yearQuery;
        }
        $user = Auth::user();
        $ongoing_transaction = Transaction::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('service_type_id', 1)
            ->where('finish_date', null)
            ->orderBy('created_at', 'DESC')
            ->get();
        $ongoing_priority_transaction = Transaction::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('service_type_id', 2)
            ->where('finish_date', null)
            ->orderBy('created_at', 'DESC')
            ->get();
        $finished_transaction = Transaction::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('finish_date', '!=', null)
            ->orderBy('created_at', 'DESC')
            ->get();
        $status = Status::all();
        $tahun = Transaction::selectRaw('YEAR(created_at) as Tahun')->distinct()->get();
        return view('admin.riwayat_transaksi', compact('user', 'status', 'tahun', 'year', 'month', 'ongoing_transaction', 'ongoing_priority_transaction', 'finished_transaction'));
    }

    /**
     * Fungsi untuk mengambil detail transaksi dari ajax
     */
    public function ambilDetailTransaksi(Request $request)
    {
        $detail_transaksi = TransactionDetail::with(['transaction', 'transaction.service_type', 'price_list', 'price_list.item', 'price_list.service', 'price_list.category'])->where('transaction_id', $request->input('id_transaksi'))->get();
        echo json_encode($detail_transaksi);
    }

    /**
     * Fungsi untuk mengubah status transaksi dari sedang dikerjakan menjadi selesai
     */
    public function ubahStatusTransaksi(Request $request)
    {
        $tgl = null;
        // Jika status 3 maka artinya sudah selesai, set tgl menjadi tgl selesai
        if ($request->input('val') == 3) {
            $tgl = date('Y-m-d H:i:s');
        }

        $transaction = Transaction::where('id', $request->input('id_transaksi'))->first();
        $transaction->status_id = $request->input('val');
        $transaction->finish_date = $tgl;
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
        $barang = Item::all();
        $servis = Service::all();
        $kategori = Category::all();
        $serviceType = ServiceType::all();
        return view('admin.harga', compact('user', 'satuan', 'kiloan', 'barang', 'servis', 'kategori', 'serviceType'));
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

        $price_list = new PriceList([
            'item_id' => $request->input('barang'),
            'category_id' => $request->input('kategori'),
            'service_id' => $request->input('servis'),
            'price' => $request->input('harga')
        ]);
        $price_list->save();

        return redirect('admin/harga')->with('success', 'Harga berhasil ditambahkan!');
    }

    /**
     * Fungsi untuk mengambil harga untuk ajax
     */
    public function ambilHarga(Request $request)
    {
        $id_harga = $request->input('id_harga');
        $harga = PriceList::where('id', $id_harga)->first();
        echo json_encode($harga);
    }

    /**
     * Fungsi untuk mengubah harga
     */
    public function ubahHarga(Request $request)
    {
        $id_harga = $request->input('id_harga');
        $price_list = PriceList::where('id', $id_harga)->first();
        $price_list->price = $request->input('harga');
        $price_list->save();
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
        $saran = ComplaintSuggestion::where([
            'type' => 1,
            'reply' => ''
        ])->get();
        $komplain = ComplaintSuggestion::where([
            'type' => 2,
            'reply' => ''
        ])->get();
        $jumlah = ComplaintSuggestion::where('reply', '')->count();
        return view('admin.saran', compact('user', 'saran', 'komplain', 'jumlah'));
    }

    /**
     * Fungsi untuk mengambil isi saran komplain melalui ajax
     */
    public function ambilSaranKomplain(Request $request)
    {
        $isi = ComplaintSuggestion::where('id', $request->input('id'))->first();
        echo json_encode($isi);
    }

    /**
     * Fungsi untuk mengirim balasan dari saran komplain
     */
    public function kirimBalasan(Request $request)
    {
        $complaint_suggestion = ComplaintSuggestion::where('id', $request->input('id'))->first();
        $complaint_suggestion->reply = $request->input('balasan');
        $complaint_suggestion->save();
    }

    /**
     * Fungsi untuk menampilkan halaman laporan keuangan
     */
    public function laporan()
    {
        $user = Auth::user();
        $tahun = Transaction::selectRaw('YEAR(created_at) as Tahun')->distinct()->get();
        $bulan = Transaction::selectRaw('MONTH(created_at) as Bulan')->distinct()->get();
        return view('admin.laporan', compact('user', 'bulan', 'tahun'));
    }

    /**
     * Fungsi untuk mendapatkan bulan berdasarkan tahun transaksi
     */
    public function getMonth(Request $request)
    {
        $tahun = $request->input('tahun');
        $bulan = Transaction::whereYear('created_at', $tahun)->selectRaw('MONTH(created_at) as Bulan')->distinct()->get();
        echo json_encode($bulan);
    }

    /**
     * Fungsi untuk mencetak laporan dengan konversi ke pdf
     */
    public function cetakLaporan(Request $request)
    {
        $bulan_num = $request->input('bulan');
        $tahun = $request->input('tahun');
        $dateObj = DateTime::createFromFormat('!m', $bulan_num);
        $bulan = $dateObj->format('F');
        $pendapatan = Transaction::whereMonth('created_at', $bulan_num)
            ->whereYear('created_at', $tahun)->sum('total');
        $totalTransaksi = Transaction::whereMonth('created_at', $bulan_num)
            ->whereYear('created_at', $tahun)->count();
        $pdf = PDF::loadview('admin.laporan_pdf', compact('bulan', 'tahun', 'pendapatan', 'totalTransaksi'));
        // return $pdf->download('laporan-keuangan-' . $bulan . '-' . $tahun . '.pdf');
        return $pdf->stream();
    }

    public function laporanview(Request $request)
    {
        $bulan_num = 8;
        $tahun = 2020;
        $dateObj = DateTime::createFromFormat('!m', $bulan_num);
        $bulan = $dateObj->format('F');
        $pendapatan = Transaction::whereMonth('created_at', $bulan_num)
            ->whereYear('created_at', $tahun)->sum('total');
        $totalTransaksi = Transaction::whereMonth('created_at', $bulan_num)
            ->whereYear('created_at', $tahun)->count();
        return view('admin.laporan_pdf', compact('bulan', 'tahun', 'pendapatan', 'totalTransaksi'));
    }
}
