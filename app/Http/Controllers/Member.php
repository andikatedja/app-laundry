<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Member extends Controller
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
        $transaksi_terakhir = DB::table('transaksi')->select('id_transaksi', 'tgl_masuk', 'transaksi.id_status', 'status.nama_status')
            ->join('status', 'transaksi.id_status', '=', 'status.id_status')->where('id_user', '=', $user->id_user)->orderBy('tgl_masuk')->limit(5)->get();
        return view('member.index', compact('user', 'transaksi_terakhir'));
    }

    public function harga()
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        $satuan = DB::table('daftar_harga')->select('daftar_harga.harga', 'servis.nama_servis', 'barang.nama_barang')
            ->join('barang', 'daftar_harga.id_barang', '=', 'barang.id_barang')
            ->join('servis', 'daftar_harga.id_servis', '=', 'servis.id_servis')->where('daftar_harga.id_kategori', '=', 's')->get();
        $kiloan = DB::table('daftar_harga')->select('daftar_harga.harga', 'servis.nama_servis', 'barang.nama_barang')
            ->join('barang', 'daftar_harga.id_barang', '=', 'barang.id_barang')
            ->join('servis', 'daftar_harga.id_servis', '=', 'servis.id_servis')->where('daftar_harga.id_kategori', '=', 'k')->get();
        return view('member.harga', compact('user', 'satuan', 'kiloan'));
    }

    public function riwayatTransaksi()
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        $transaksi = DB::table('transaksi')->select('id_transaksi', 'tgl_masuk', 'transaksi.id_status', 'status.nama_status')
            ->join('status', 'transaksi.id_status', '=', 'status.id_status')->where('id_user', '=', $user->id_user)->orderBy('tgl_masuk')->get();
        return view('member.riwayat', compact('user', 'transaksi'));
    }

    public function poin()
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        return view('member.poin', compact('user'));
    }

    public function edit()
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        return view('member.edit', compact('user'));
    }

    public function saranKomplain()
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        $saran_komplain = DB::table('saran_komplain')->where('id_user', '=', $user->id_user)->get();
        return view('member.saran', compact('user', 'saran_komplain'));
    }

    public function editprofil(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'jenis_kelamin' => 'required',
            'alamat' => 'required',
            'telp' => 'required'
        ]);
        $nama = $request->input('name');
        $jenis_kelamin = $request->input('jenis_kelamin');
        $alamat = $request->input('alamat');
        $no_telp = $request->input('telp');

        $user_id = DB::table('users')->where('email', '=', $this->logged_email)->pluck('id');

        $filename = 'default.jpg';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = $user_id[0] . '.' . $extension;
            $path = public_path('img/profile');
            $image->move($path, $filename);
        }

        DB::table('users_info')->where('id_user', '=', $user_id)->update([
            'nama' => $nama,
            'jenis_kelamin' => $jenis_kelamin,
            'alamat' => $alamat,
            'no_telp' => $no_telp,
            'profil' => $filename
        ]);

        return redirect('member/edit')->with('success', 'Profil berhasil diedit!');
    }

    public function resetfoto()
    {
        $user_id = DB::table('users')->where('email', '=', $this->logged_email)->pluck('id');
        DB::table('users_info')->where('id_user', '=', $user_id)->update([
            'profil' => 'default.jpg'
        ]);
        return redirect('member/edit')->with('success', 'Foto profil berhasil direset');
    }

    public function editpassword(Request $request)
    {
        $request->validate([
            'current-password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);
        $db_password = DB::table('users')->where('email', '=', $this->logged_email)->pluck('password');

        //Cek apakah password lama sama
        if (!Hash::check($request->input('current-password'), $db_password[0])) {
            return redirect('member/edit')->with('error', 'Kata sandi sekarang salah!');
        }

        $password_hash = Hash::make($request->input('password'));
        DB::table('users')->where('email', '=', $this->logged_email)->update([
            'password' => $password_hash
        ]);
        return redirect('member/edit')->with('success', 'Kata sandi berhasil diubah');
    }

    public function kirimSaranKomplain(Request $request)
    {
        $request->validate([
            'isi_sarankomplain' => 'required'
        ]);
        $id_user = DB::table('users')->where('email', '=', $this->logged_email)->pluck('id');
        DB::table('saran_komplain')->insert([
            'id' => null,
            'tgl' => date('Y-m-d H:i:s'),
            'isi' => $request->input('isi_sarankomplain'),
            'tipe' => $request->input('tipe'),
            'id_user' => $id_user[0]
        ]);

        return redirect('member/saran')->with('success', 'Saran/komplain berhasil dikirim!');
    }

    public function detailTransaksi($id_transaksi)
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        $transaksi = DB::table('detail_transaksi')->select('barang.nama_barang', 'kategori.nama_kategori', 'servis.nama_servis', 'detail_transaksi.banyak', 'detail_transaksi.sub_total')
            ->join('barang', 'detail_transaksi.id_barang', '=', 'barang.id_barang')
            ->join('kategori', 'detail_transaksi.id_kategori', '=', 'kategori.id_kategori')
            ->join('servis', 'detail_transaksi.id_servis', '=', 'servis.id_servis')->where('detail_transaksi.id_transaksi', '=', $id_transaksi)
            ->get();
        return view('member.detail', compact('user', 'transaksi', 'id_transaksi'));
    }
}
