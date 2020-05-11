<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function index()
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        $transaksi_terbaru = DB::table('transaksi')->select('id_transaksi', 'tgl_masuk', 'transaksi.id_status', 'status.nama_status')
            ->join('status', 'transaksi.id_status', '=', 'status.id_status')->orderBy('tgl_masuk', 'desc')->limit(10)->get();
        $banyak_member = DB::table('users')->where('role', '=', 2)->count();
        $banyak_transaksi = DB::table('transaksi')->count();
        return view('admin.index', compact('user', 'transaksi_terbaru', 'banyak_member', 'banyak_transaksi'));
    }

    public function inputTransaksi(Request $request)
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        // $barang = DB::table('daftar_harga')->join('barang', 'daftar_harga.id_barang', '=', 'barang.id_barang')
        //     ->join('kategori', 'daftar_harga.id_kategori', '=', 'kategori.id_kategori')
        //     ->join('servis', 'daftar_harga.id_servis', '=', 'servis.id_servis')->select('barang.nama_barang')->distinct()->get();
        // $servis = DB::table('daftar_harga')->join('barang', 'daftar_harga.id_barang', '=', 'barang.id_barang')
        //     ->join('kategori', 'daftar_harga.id_kategori', '=', 'kategori.id_kategori')
        //     ->join('servis', 'daftar_harga.id_servis', '=', 'servis.id_servis')->select('service.nama_service')->distinct()->get();
        // $kategori = DB::table('daftar_harga')->join('barang', 'daftar_harga.id_barang', '=', 'barang.id_barang')
        //     ->join('kategori', 'daftar_harga.id_kategori', '=', 'kategori.id_kategori')
        //     ->join('servis', 'daftar_harga.id_servis', '=', 'servis.id_servis')->select('kategori.nama_kategori')->distinct()->get();

        $barang = DB::table('barang')->get();
        $kategori = DB::table('kategori')->get();
        $servis = DB::table('servis')->get();

        if ($request->session()->has('transaksi') && $request->session()->has('id_member_transaksi')) {
            $transaksi = $request->session()->get('transaksi');
            $id_member_transaksi = $request->session()->get('id_member_transaksi');
            return view('admin.input_transaksi', compact('user', 'barang', 'kategori', 'servis', 'transaksi', 'id_member_transaksi'));
        }
        return view('admin.input_transaksi', compact('user', 'barang', 'kategori', 'servis'));
    }

    public function tambahTransaksi(Request $request)
    {
        $ada_harga = DB::table('daftar_harga')->where([
            'id_barang' => $request->input('barang'),
            'id_kategori' => $request->input('kategori'),
            'id_servis' => $request->input('servis')
        ])->exists();

        if (!$ada_harga) {
            return redirect('admin/input-transaksi')->with('error', 'Harga tidak ditemukan!');
        }

        $id_member = $request->input('id_member');
        $id_barang = $request->input('barang');
        $id_servis = $request->input('servis');
        $id_kategori = $request->input('kategori');
        $banyak = $request->input('banyak');

        $dbharga = DB::table('daftar_harga')->where([
            'id_barang' => $id_barang,
            'id_kategori' => $id_kategori,
            'id_servis' => $id_servis
        ])->pluck('harga');

        $harga = $dbharga[0] * $banyak;

        $nama_barang = DB::table('barang')->where('id_barang', '=', $id_barang)->pluck('nama_barang');
        $nama_servis = DB::table('servis')->where('id_servis', '=', $id_servis)->pluck('nama_servis');
        $nama_kategori = DB::table('kategori')->where('id_kategori', '=', $id_kategori)->pluck('nama_kategori');

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

        if (!$request->session()->has('transaksi') && !$request->session()->has('id_member_transaksi')) {
            $request->session()->put('transaksi', $data);
            $request->session()->put('id_member_transaksi', $id_member);
        } else {
            $exist = 0;
            $transaksi = $request->session()->get('transaksi');
            foreach ($transaksi as $k => $v) {
                if ($transaksi[$k]['id_barang'] == $id_barang && $transaksi[$k]['id_kategori'] == $id_kategori && $transaksi[$k]['id_servis'] == $id_servis) {
                    $transaksi[$k]['banyak'] += $banyak;
                    $transaksi[$k]['harga'] += $harga;
                    $exist++;
                }
            }
            $request->session()->put('transaksi', $transaksi);
            if ($exist == 0) {
                $oldtransaksi = $request->session()->get('transaksi');
                $newtransaksi = array_merge_recursive($oldtransaksi, $data);
                $request->session()->put('transaksi', $newtransaksi);
            }
        }

        return redirect('admin/input-transaksi');
    }

    public function hapusTransaksi($row_id, Request $request)
    {
        $newtransaksi = $request->session()->get('transaksi');
        unset($newtransaksi[$row_id]);

        if ($newtransaksi == []) {
            $request->session()->forget('transaksi');
            $request->session()->forget('id_member_transaksi');
            return redirect('admin/input-transaksi');
        }

        $request->session()->put('transaksi', $newtransaksi);
        return redirect('admin/input-transaksi');
    }

    public function simpanTransaksi(Request $request)
    {
        $id_member = $request->session()->get('id_member_transaksi');
        $transaksi = $request->session()->get('transaksi');
        $total_harga = 0;
        foreach ($transaksi as $key => $value) {
            $total_harga += $transaksi[$key]['harga'];
        }

        $id_transaksi = DB::table('transaksi')->insertGetId([
            'tgl_masuk' => date('Y-m-d H:i:s'),
            'id_status' => 1,
            'id_user' => $id_member,
            'tgl_selesai' => null,
            'total_harga' => $total_harga
        ]);

        foreach ($transaksi as $key => $value) {
            DB::table('detail_transaksi')->insert([
                'id_transaksi' => $id_transaksi,
                'id_barang' => $transaksi[$key]['id_barang'],
                'id_kategori' => $transaksi[$key]['id_kategori'],
                'id_servis' => $transaksi[$key]['id_servis'],
                'banyak' => $transaksi[$key]['banyak'],
                'sub_total' => $transaksi[$key]['harga']
            ]);
        }

        $poin = DB::table('users_info')->where('id_user', '=', $id_member)->pluck('poin')[0];
        $poin += 1;
        DB::table('users_info')->where('id_user', '=', $id_member)->update([
            'poin' => $poin
        ]);
        $request->session()->forget('transaksi');
        $request->session()->forget('id_member_transaksi');
        return redirect('admin/input-transaksi')->with('success', 'Transaksi berhasil disimpan')->with('id_trs', $id_transaksi);
    }

    public function cetakTransaksi($id)
    {
        $member = DB::table('transaksi')->join('users_info', 'transaksi.id_user', 'users_info.id_user')->where('transaksi.id_transaksi', '=', $id)->pluck('users_info.nama');
        $tanggal = DB::table('transaksi')->join('users_info', 'transaksi.id_user', 'users_info.id_user')->where('transaksi.id_transaksi', '=', $id)->pluck('transaksi.tgl_masuk');
        $total = DB::table('transaksi')->join('users_info', 'transaksi.id_user', 'users_info.id_user')->where('transaksi.id_transaksi', '=', $id)->pluck('transaksi.total_harga');
        $transaksi = $detail_transaksi = DB::table('detail_transaksi')->select('barang.nama_barang', 'kategori.nama_kategori', 'servis.nama_servis', 'detail_transaksi.banyak', 'detail_transaksi.sub_total')
            ->join('barang', 'detail_transaksi.id_barang', '=', 'barang.id_barang')
            ->join('kategori', 'detail_transaksi.id_kategori', '=', 'kategori.id_kategori')
            ->join('servis', 'detail_transaksi.id_servis', '=', 'servis.id_servis')->where('detail_transaksi.id_transaksi', '=', $id)
            ->get();
        return view('admin.cetak_transaksi', compact('id', 'member', 'tanggal', 'total', 'transaksi'));
    }

    public function hapusSessTransaksi(Request $request)
    {
        $request->session()->forget('transaksi');
        $request->session()->forget('id_member_transaksi');
        return redirect('admin/input-transaksi');
    }

    public function riwayatTransaksi()
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        $transaksi = DB::table('transaksi')->join('users_info', 'transaksi.id_user', '=', 'users_info.id_user')
            ->select('transaksi.*', 'users_info.nama')->get();
        return view('admin.riwayat_transaksi', compact('user', 'transaksi'));
    }

    public function ambilDetailTransaksi(Request $request)
    {
        $id_transaksi = $request->input('id_transaksi');
        $detail_transaksi = DB::table('detail_transaksi')->select('barang.nama_barang', 'kategori.nama_kategori', 'servis.nama_servis', 'detail_transaksi.banyak', 'detail_transaksi.sub_total')
            ->join('barang', 'detail_transaksi.id_barang', '=', 'barang.id_barang')
            ->join('kategori', 'detail_transaksi.id_kategori', '=', 'kategori.id_kategori')
            ->join('servis', 'detail_transaksi.id_servis', '=', 'servis.id_servis')->where('detail_transaksi.id_transaksi', '=', $id_transaksi)
            ->get();
        echo json_encode($detail_transaksi);
    }

    public function ubahStatusTransaksi(Request $request)
    {
        $id_transaksi = $request->input('id_transaksi');
        DB::table('transaksi')->where('id_transaksi', '=', $id_transaksi)->update([
            'id_status' => 2,
            'tgl_selesai' => date('Y-m-d H:i:s')
        ]);
    }

    public function harga()
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
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

    public function tambahHarga(Request $request)
    {
        $request->validate([
            'harga' => 'required'
        ]);

        $ada_harga = DB::table('daftar_harga')->where([
            'id_barang' => $request->input('barang'),
            'id_kategori' => $request->input('kategori'),
            'id_servis' => $request->input('servis')
        ])->exists();

        if ($ada_harga) {
            return redirect('admin/harga')->with('error', 'Harga tidak dapat ditambah karena sudah tersedia!');
        }

        DB::table('daftar_harga')->insert([
            'id_harga' => null,
            'id_barang' => $request->input('barang'),
            'id_kategori' => $request->input('kategori'),
            'id_servis' => $request->input('servis')
        ]);

        return redirect('admin/harga')->with('success', 'Harga berhasil ditambahkan!');
    }

    public function ambilHarga(Request $request)
    {
        $id_harga = $request->input('id_harga');
        $harga = DB::table('daftar_harga')->select('harga')->where('id_harga', '=', $id_harga)->get();
        echo json_encode($harga);
    }

    public function ubahHarga(Request $request)
    {
        $id_harga = $request->input('id_harga');
        DB::table('daftar_harga')->where('id_harga', '=', $id_harga)->update([
            'harga' => $request->input('harga')
        ]);
        return redirect('admin/harga')->with('success', 'Harga berhasil diubah!');
    }

    public function members()
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        $members = DB::table('users_info')->join('users', 'users.id', '=', 'users_info.id_user')->select('users_info.*')->where('role', '=', 2)->get();
        return view('admin.members', compact('user', 'members'));
    }

    public function saran()
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        $saran = DB::table('saran_komplain')->join('users_info', 'saran_komplain.id_user', '=', 'users_info.id_user')
            ->select('saran_komplain.id', 'users_info.nama')
            ->where([
                'tipe' => '1',
                'balasan' => NULL
            ])->get();
        $komplain = DB::table('saran_komplain')->join('users_info', 'saran_komplain.id_user', '=', 'users_info.id_user')
            ->select('saran_komplain.id', 'users_info.nama')
            ->where([
                'tipe' => '2',
                'balasan' => NULL
            ])->get();
        $jumlah = DB::table('saran_komplain')->where('balasan', '=', NULL)->count();
        return view('admin.saran', compact('user', 'saran', 'komplain', 'jumlah'));
    }

    public function ambilSaranKomplain(Request $request)
    {
        $isi = DB::table('saran_komplain')->select('isi')->where('id', '=', $request->input('id'))->get();
        echo json_encode($isi);
    }

    public function kirimBalasan(Request $request)
    {
        DB::table('saran_komplain')->where('id', '=', $request->input('id'))->update([
            'balasan' => $request->input('balasan')
        ]);
    }

    public function laporan()
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        $tahun = DB::table('transaksi')->selectRaw('YEAR(tgl_masuk) as Tahun')->distinct()->get();
        $bulan = DB::table('transaksi')->selectRaw('MONTH(tgl_masuk) as Bulan')->distinct()->get();
        return view('admin.laporan', compact('user', 'bulan', 'tahun'));
    }

    public function cetakLaporan(Request $request)
    {
        $bulan_num = $request->input('bulan');
        $tahun = $request->input('tahun');
        $dateObj   = DateTime::createFromFormat('!m', $bulan_num);
        $bulan = $dateObj->format('F');
        $pendapatan = DB::table('transaksi')->whereMonth('tgl_masuk', '=', $bulan_num)
            ->whereYear('tgl_masuk', '=', $tahun)->sum('total_harga');
        $pdf = PDF::loadview('admin.laporan_pdf', compact('bulan', 'tahun', 'pendapatan'));
        return $pdf->download('laporan-keuangan-' . $bulan . '-' . $tahun);
    }
}
