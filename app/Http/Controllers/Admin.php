<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Admin_model;
use PDF;
use DateTime;

class Admin extends Controller
{
    protected $logged_email;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->logged_email = session()->get('login');
            return $next($request);
        });
    }

    /*
    | Fungsi index untuk menampilkan halaman dashboard admin
    */
    public function index()
    {
        $user = Admin_model::getUserInfo($this->logged_email);
        $transaksi_terbaru = DB::table('transaksi')->select('id_transaksi', 'tgl_masuk', 'transaksi.id_status', 'status.nama_status')
            ->join('status', 'transaksi.id_status', '=', 'status.id_status')->orderBy('tgl_masuk', 'desc')->limit(10)->get();
        $banyak_member = DB::table('users')->where('role', '=', 2)->count();
        $banyak_transaksi = DB::table('transaksi')->count();
        return view('admin.index', compact('user', 'transaksi_terbaru', 'banyak_member', 'banyak_transaksi'));
    }

    /*
    | Fungsi untuk menampilkan halaman input transaksi
    */
    public function inputTransaksi(Request $request)
    {
        $user = Admin_model::getUserInfo($this->logged_email);
        $barang = DB::table('barang')->get();
        $kategori = DB::table('kategori')->get();
        $servis = DB::table('servis')->get();

        // Mengecek apakah ada sesi transaksi atau tidak
        if ($request->session()->has('transaksi') && $request->session()->has('id_member_transaksi')) {
            $transaksi = $request->session()->get('transaksi');
            $id_member_transaksi = $request->session()->get('id_member_transaksi');
            // Mengambil voucher yang dimiliki member
            $vouchers = DB::table('users_vouchers')->join('vouchers', 'users_vouchers.id_voucher', '=', 'vouchers.id_voucher')
                ->select('users_vouchers.id', 'vouchers.nama_voucher', 'vouchers.potongan')
                ->where([
                    'users_vouchers.id_member' => $id_member_transaksi,
                    'users_vouchers.used' => NULL
                ])->get();
            $total_harga = 0;
            foreach ($transaksi as $key => $value) {
                $total_harga += $transaksi[$key]['harga'];
            }
            return view('admin.input_transaksi', compact('user', 'barang', 'kategori', 'servis', 'transaksi', 'id_member_transaksi', 'total_harga', 'vouchers'));
        }
        return view('admin.input_transaksi', compact('user', 'barang', 'kategori', 'servis'));
    }

    /*
    | Fungsi untuk menambahkan transaksi baru ke dalam sesi (session) transaksi
    */
    public function tambahTransaksi(Request $request)
    {
        $id_barang = $request->input('barang');
        $id_servis = $request->input('servis');
        $id_kategori = $request->input('kategori');
        $id_member = $request->input('id_member');
        $banyak = $request->input('banyak');

        // Cek apakah harga tertera pada database
        if (!Admin_model::cekHarga($id_barang, $id_kategori, $id_servis)) {
            return redirect('admin/input-transaksi')->with('error', 'Harga tidak ditemukan!');
        }

        //Cek member
        if ($id_member != null && !Admin_model::cekMember($id_member)) {
            return redirect('admin/input-transaksi')->with('error', 'Member tidak ditemukan!');
        }

        // Ambil harga dari database
        $dbharga = Admin_model::getHarga($id_barang, $id_kategori, $id_servis);

        // Hitung subtotal
        $harga = $dbharga[0] * $banyak;

        // Ambil nama barang, servis, kategori berdasarkan id
        $nama_barang = DB::table('barang')->where('id_barang', '=', $id_barang)->pluck('nama_barang');
        $nama_servis = DB::table('servis')->where('id_servis', '=', $id_servis)->pluck('nama_servis');
        $nama_kategori = DB::table('kategori')->where('id_kategori', '=', $id_kategori)->pluck('nama_kategori');

        // Membuat row baru untuk disimpan dalam session
        $row_id = md5($id_member . serialize($id_barang) . serialize($id_servis) . serialize($id_kategori));

        $data = [
            $row_id => [
                'id_barang' => $id_barang,
                'nama_barang' => $nama_barang[0],
                'id_kategori' => $id_kategori,
                'nama_kategori' => $nama_kategori[0],
                'id_servis' => $id_servis,
                'nama_servis' => $nama_servis[0],
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
            foreach ($transaksi as $k => $v) {
                if ($transaksi[$k]['id_barang'] == $id_barang && $transaksi[$k]['id_kategori'] == $id_kategori && $transaksi[$k]['id_servis'] == $id_servis) {
                    $transaksi[$k]['banyak'] += $banyak;
                    $transaksi[$k]['harga'] += $harga;
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

    /*
    | Fungsi untuk menghapus transaksi dari sesi transaksi, diakses dari menekan tombol hapus
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

    /*
    | Fungsi untuk menyimpan transaksi dari sesi transaksi
    */
    public function simpanTransaksi(Request $request)
    {
        $id_member = $request->session()->get('id_member_transaksi');
        $id_admin = DB::table('users')->where([
            'email' => $this->logged_email,
            'role' => 1
        ])->pluck('id')[0];
        $transaksi = $request->session()->get('transaksi');
        $total_harga = 0;
        foreach ($transaksi as $key => $value) {
            $total_harga += $transaksi[$key]['harga'];
        }
        $potongan = 0;

        //Cek apakah ada voucher yang digunakan
        if ($request->input('voucher') != 0) {
            // Ambil banyak potongan dari database
            $potongan = DB::table('users_vouchers')->join('vouchers', 'users_vouchers.id_voucher', '=', 'vouchers.id_voucher')
                ->where('users_vouchers.id', '=', $request->input('voucher'))->pluck('potongan')[0];

            // Kurangi harga dengan potongan
            $total_harga -= $potongan;
            if ($total_harga < 0) {
                $total_harga = 0;
            }

            // Ganti status used pada tabel users_vouchers
            DB::table('users_vouchers')->where('id', '=', $request->input('voucher'))->update([
                'used' => 1 //True
            ]);
        }

        $id_transaksi = Admin_model::simpanTransaksi($transaksi, $id_member, $total_harga, $id_admin, $potongan);
        $request->session()->forget('transaksi');
        $request->session()->forget('id_member_transaksi');
        return redirect('admin/input-transaksi')->with('success', 'Transaksi berhasil disimpan')->with('id_trs', $id_transaksi);
    }

    /*
    | Fungsi untuk mencetak transaksi
    */
    public function cetakTransaksi($id)
    {
        $dataTransaksi = DB::table('transaksi')->where('transaksi.id_transaksi', '=', $id)
            ->select('transaksi.tgl_masuk', 'transaksi.total_harga', 'transaksi.potongan')->get();
        $member = DB::table('transaksi')->join('users', 'transaksi.id_member', 'users.id')->where('transaksi.id_transaksi', '=', $id)->pluck('users.nama');
        $admin = DB::table('transaksi')->join('users', 'transaksi.id_admin', 'users.id')->where('transaksi.id_transaksi', '=', $id)->pluck('users.nama');
        $transaksi = Admin_model::getDetailTransaksi($id);
        return view('admin.cetak_transaksi', compact('id', 'member', 'dataTransaksi', 'transaksi', 'admin'));
    }

    /*
    | Fungsi untuk menghapus sesi transaksi
    */
    public function hapusSessTransaksi(Request $request)
    {
        $request->session()->forget('transaksi');
        $request->session()->forget('id_member_transaksi');
        return redirect('admin/input-transaksi');
    }

    /*
    | Fungsi untuk menampilkan halaman riwayat transaksi
    */
    public function riwayatTransaksi()
    {
        $user = Admin_model::getUserInfo($this->logged_email);
        $transaksi = Admin_model::getRiwayatTransaksi();
        $status = DB::table('status')->get();
        return view('admin.riwayat_transaksi', compact('user', 'transaksi', 'status'));
    }

    /*
    | Fungsi untuk mengambil detail transaksi dari ajax
    */
    public function ambilDetailTransaksi(Request $request)
    {
        $detail_transaksi = Admin_model::getDetailTransaksi($request->input('id_transaksi'));
        echo json_encode($detail_transaksi);
    }

    /*
    | Fungsi untuk mengubah status transaksi dari sedang dikerjakan menjadi selesai
    */
    public function ubahStatusTransaksi(Request $request)
    {
        $tgl = null;
        if ($request->input('val') == 3) {
            $tgl = date('Y-m-d H:i:s');
        }

        DB::table('transaksi')->where('id_transaksi', '=', $request->input('id_transaksi'))->update([
            'id_status' => $request->input('val'),
            'tgl_selesai' => $tgl
        ]);
    }

    /*
    | Fungsi untuk menampilkan halaman daftar harga
    */
    public function harga()
    {
        $user = Admin_model::getUserInfo($this->logged_email);
        $satuan = DB::table('daftar_harga')->select('daftar_harga.id_harga', 'daftar_harga.harga', 'servis.nama_servis', 'barang.nama_barang')
            ->join('barang', 'daftar_harga.id_barang', '=', 'barang.id_barang')
            ->join('servis', 'daftar_harga.id_servis', '=', 'servis.id_servis')->where('daftar_harga.id_kategori', '=', 's')->get();
        $kiloan = DB::table('daftar_harga')->select('daftar_harga.id_harga', 'daftar_harga.harga', 'servis.nama_servis', 'barang.nama_barang')
            ->join('barang', 'daftar_harga.id_barang', '=', 'barang.id_barang')
            ->join('servis', 'daftar_harga.id_servis', '=', 'servis.id_servis')->where('daftar_harga.id_kategori', '=', 'k')->get();
        $barang = DB::table('barang')->get();
        $servis = DB::table('servis')->get();
        $kategori = DB::table('kategori')->get();
        return view('admin.harga', compact('user', 'satuan', 'kiloan', 'barang', 'servis', 'kategori'));
    }

    /*
    | Fungsi untuk menambah harga baru
    */
    public function tambahHarga(Request $request)
    {
        $request->validate([
            'harga' => 'required'
        ]);

        if (Admin_model::cekHarga($request->input('barang'), $request->input('kategori'), $request->input('servis'))) {
            return redirect('admin/harga')->with('error', 'Harga tidak dapat ditambah karena sudah tersedia!');
        }

        Admin_model::tambahHarga(
            $request->input('barang'),
            $request->input('kategori'),
            $request->input('servis'),
            $request->input('harga')
        );

        return redirect('admin/harga')->with('success', 'Harga berhasil ditambahkan!');
    }

    /*
    | Fungsi untuk mengambil harga untuk ajax
    */
    public function ambilHarga(Request $request)
    {
        $id_harga = $request->input('id_harga');
        $harga = DB::table('daftar_harga')->select('harga')->where('id_harga', '=', $id_harga)->get();
        echo json_encode($harga);
    }

    /*
    | Fungsi untuk mengubah harga
    */
    public function ubahHarga(Request $request)
    {
        $id_harga = $request->input('id_harga');
        DB::table('daftar_harga')->where('id_harga', '=', $id_harga)->update([
            'harga' => $request->input('harga')
        ]);
        return redirect('admin/harga')->with('success', 'Harga berhasil diubah!');
    }

    /*
    | Fungsi untuk menambah barang baru
    */
    public function tambahBarang(Request $request)
    {
        DB::table('barang')->insert([
            'id_barang' => NULL,
            'nama_barang' => $request->input('barang')
        ]);
        return redirect('admin/harga')->with('success', 'Barang baru berhasil ditambah!');
    }

    /*
    | Fungsi untuk menambah servis baru
    */
    public function tambahServis(Request $request)
    {
        DB::table('servis')->insert([
            'id_servis' => NULL,
            'nama_servis' => $request->input('servis')
        ]);
        return redirect('admin/harga')->with('success', 'Servis baru berhasil ditambah!');
    }


    /*
    | Fungsi untuk menampilkan halaman daftar member
    */
    public function members()
    {
        $user = Admin_model::getUserInfo($this->logged_email);
        $members = DB::table('users')->where('role', '=', 2)->get();
        return view('admin.members', compact('user', 'members'));
    }

    /*
    | Fungsi untuk menampilkan halaman voucher
    */
    public function voucher()
    {
        $user = Admin_model::getUserInfo($this->logged_email);
        $vouchers = DB::table('vouchers')->get();
        return view('admin.voucher', compact('user', 'vouchers'));
    }

    /*
    | Fungsi untuk menambahkan voucher baru
    */
    public function tambahVoucher(Request $request)
    {
        // Cek apakah potongan ada yang sama di database
        if (DB::table('vouchers')->where('potongan', '=', $request->input('potongan'))->exists()) {
            return redirect('admin/voucher')->with('error', 'Voucher potongan ' . $request->input('potongan') . ' sudah ada');
        }

        // Masukkan potongan ke dalam tabel vouchers
        DB::table('vouchers')->insert([
            'nama_voucher' => 'Potongan ' . number_format($request->input('potongan'), 0, ',', '.'),
            'potongan' => $request->input('potongan'),
            'poin_need' => $request->input('poin'),
            'keterangan' => 'Mendapatkan potongan harga ' . number_format($request->input('potongan'), 0, ',', '.') . ' dari total transaksi',
            'aktif' => 1
        ]);
        return redirect('admin/voucher')->with('success', 'Voucher baru berhasil ditambah!');
    }

    /*
    | Fungsi untuk mengubah status aktif voucher
    */
    public function ubahAktifVoucher(Request $request)
    {
        // Cek apakah status aktif atau tidak aktif
        // 1 artinya aktif, 0 artinya tidak aktif
        $vouchersts = DB::table('vouchers')->where('id_voucher', '=', $request->input('id'))->pluck('aktif')[0];

        // Jika 1 maka ubah ke 0
        if ($vouchersts == 1) {
            DB::table('vouchers')->where('id_voucher', '=', $request->input('id'))->update([
                'aktif' => 0
            ]);
        } else {
            // Ubah ke 1
            DB::table('vouchers')->where('id_voucher', '=', $request->input('id'))->update([
                'aktif' => 1
            ]);
        }
    }

    /*
    | Fungsi untuk menampilkan halaman saran komplain
    */
    public function saran()
    {
        $user = Admin_model::getUserInfo($this->logged_email);
        $saran = Admin_model::getSaranKomplain(1);
        $komplain = Admin_model::getSaranKomplain(2);
        $jumlah = DB::table('saran_komplain')->where('balasan', '=', NULL)->count();
        return view('admin.saran', compact('user', 'saran', 'komplain', 'jumlah'));
    }

    /*
    | Fungsi untuk mengambil isi saran komplain melalui ajax
    */
    public function ambilSaranKomplain(Request $request)
    {
        $isi = DB::table('saran_komplain')->select('isi')->where('id', '=', $request->input('id'))->get();
        echo json_encode($isi);
    }

    /*
    | Fungsi untuk mengirim balasan dari saran komplain
    */
    public function kirimBalasan(Request $request)
    {
        DB::table('saran_komplain')->where('id', '=', $request->input('id'))->update([
            'balasan' => $request->input('balasan')
        ]);
    }

    /*
    | Fungsi untuk menampilkan halaman laporan keuangan
    */
    public function laporan()
    {
        $user = Admin_model::getUserInfo($this->logged_email);
        $tahun = DB::table('transaksi')->selectRaw('YEAR(tgl_masuk) as Tahun')->distinct()->get();
        $bulan = DB::table('transaksi')->selectRaw('MONTH(tgl_masuk) as Bulan')->distinct()->get();
        return view('admin.laporan', compact('user', 'bulan', 'tahun'));
    }

    /*
    | Fungsi untuk mencetak laporan dengan konversi ke pdf
    */
    public function cetakLaporan(Request $request)
    {
        $bulan_num = $request->input('bulan');
        $tahun = $request->input('tahun');
        $dateObj = DateTime::createFromFormat('!m', $bulan_num);
        $bulan = $dateObj->format('F');
        $pendapatan = DB::table('transaksi')->whereMonth('tgl_masuk', '=', $bulan_num)
            ->whereYear('tgl_masuk', '=', $tahun)->sum('total_harga');
        $pdf = PDF::loadview('admin.laporan_pdf', compact('bulan', 'tahun', 'pendapatan'));
        return $pdf->download('laporan-keuangan-' . $bulan . '-' . $tahun . '.pdf');
    }

    public function laporanview(Request $request)
    {
        $bulan_num = 8;
        $tahun = 2020;
        $dateObj = DateTime::createFromFormat('!m', $bulan_num);
        $bulan = $dateObj->format('F');
        $pendapatan = DB::table('transaksi')->whereMonth('tgl_masuk', '=', $bulan_num)
            ->whereYear('tgl_masuk', '=', $tahun)->sum('total_harga');
        return view('admin.laporan_pdf', compact('bulan', 'tahun', 'pendapatan'));
    }
}
