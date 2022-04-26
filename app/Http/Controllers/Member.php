<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Member_model;
use App\User;

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

    /*
    | Fungsi untuk menampilkan halaman dashboard member (Beranda)
    */
    public function index()
    {
        $user = User::where('email', '=', $this->logged_email)->first();
        $transaksi_terakhir = User::find($user->id)->transactions;
        // dd($transaksi_terakhir);
        // dd($user);


        // $user = Member_model::getUserInfo($this->logged_email);
        // $transaksi_terakhir = Member_model::getTransaksi($user->id, 5);

        return view('member.index', compact('user', 'transaksi_terakhir'));
    }

    /*
    | Fungsi untuk menampilkan daftar harga
    */
    public function harga()
    {
        $user = Member_model::getUserInfo($this->logged_email);
        $satuan = Member_model::getSatuanKiloan('s');
        $kiloan = Member_model::getSatuanKiloan('k');
        return view('member.harga', compact('user', 'satuan', 'kiloan'));
    }

    /*
    | Fungsi untuk menampilkan halaman riwayat transaksi member
    */
    public function riwayatTransaksi()
    {
        $user = Member_model::getUserInfo($this->logged_email);
        $transaksi = Member_model::getTransaksi($user->id);
        return view('member.riwayat', compact('user', 'transaksi'));
    }

    /*
    | Fungsi untuk menampilkan halaman poin
    */
    public function poin()
    {
        $user = Member_model::getUserInfo($this->logged_email);
        $vouchers = DB::table('vouchers')->where('aktif', '=', 1)->get();
        $memberVouchers = Member_model::getMemberVouchers($user->id);
        return view('member.poin', compact('user', 'vouchers', 'memberVouchers'));
    }

    /*
    | Fungsi untuk menukar poin
    */
    public function tukarPoin($id_voucher)
    {
        $user = Member_model::getUserInfo($this->logged_email);

        // Ambil data poin yang diperlukan untuk menukar voucher
        $poin = DB::table('vouchers')->where('id_voucher', '=', $id_voucher)->pluck('poin_need')[0];

        // Cek apakah poin member mencukupi untuk menukar voucher
        // Jika poin mencukupi, tambahkan ke tabel users voucher
        if ($user->poin >= $poin) {
            // Jalankan fungsi model menukar poin
            Member_model::tukarPoin($id_voucher, $user, $poin);

            //Redirect ke poin dan pesan sukses
            return redirect('member/poin')->with('success', 'Poin berhasil ditukar menjadi voucher!');
        } else {
            return redirect('member/poin')->with('error', 'Poin tidak mencukupi untuk menukar voucher!');
        }
    }

    /*
    | Fungsi untuk menampilkan halaman edit profil
    */
    public function edit()
    {
        $user = Member_model::getUserInfo($this->logged_email);
        return view('member.edit', compact('user'));
    }

    /*
    | Fungsi untuk menampilkan halaman saran komplain
    */
    public function saranKomplain()
    {
        $user = Member_model::getUserInfo($this->logged_email);
        $saran_komplain = DB::table('saran_komplain')->where('id_member', '=', $user->id)->get();
        return view('member.saran', compact('user', 'saran_komplain'));
    }

    /*
    | Fungsi untuk melakukan edit profil
    */
    public function editprofil(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'jenis_kelamin' => 'required',
            'alamat' => 'required',
            'telp' => 'required'
        ]);

        $id_member = DB::table('users')->where('email', '=', $this->logged_email)->pluck('id')[0];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = $id_member . '.' . $extension;
            $path = public_path('img/profile');
            $image->move($path, $filename);

            Member_model::updateProfil($id_member, [
                'nama' => $request->input('name'),
                'jenis_kelamin' => $request->input('jenis_kelamin'),
                'alamat' => $request->input('alamat'),
                'no_telp' => $request->input('telp'),
                'profil' => $filename
            ]);
        } else {
            Member_model::updateProfil($id_member, [
                'nama' => $request->input('name'),
                'jenis_kelamin' => $request->input('jenis_kelamin'),
                'alamat' => $request->input('alamat'),
                'no_telp' => $request->input('telp')
            ]);
        }

        return redirect('member/edit')->with('success', 'Profil berhasil diedit!');
    }

    /*
    | Fungsi untuk reset foto
    */
    public function resetfoto()
    {
        DB::table('users')->where('email', '=', $this->logged_email)->update([
            'profil' => 'default.jpg'
        ]);
        return redirect('member/edit')->with('success', 'Foto profil berhasil direset');
    }

    /*
    | Fungsi untuk melakukan update password
    */
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

    /*
    | Fungsi untuk mengirimkan saran komplain
    */
    public function kirimSaranKomplain(Request $request)
    {
        $request->validate([
            'isi_sarankomplain' => 'required'
        ]);

        $id_member = Member_model::getUserInfo($this->logged_email)->id;
        DB::table('saran_komplain')->insert([
            'id' => NULL,
            'tgl' => date('Y-m-d H:i:s'),
            'isi' => $request->input('isi_sarankomplain'),
            'tipe' => $request->input('tipe'),
            'id_member' => $id_member
        ]);

        return redirect('member/saran')->with('success', 'Saran/komplain berhasil dikirim!');
    }

    /*
    | Fungsi untuk menampilkan halaman detail transaksi
    */
    public function detailTransaksi($id_transaksi)
    {
        $user = Member_model::getUserInfo($this->logged_email);
        $transaksi = Member_model::getDetailTransaksi($id_transaksi);
        return view('member.detail', compact('user', 'transaksi', 'id_transaksi'));
    }
}
