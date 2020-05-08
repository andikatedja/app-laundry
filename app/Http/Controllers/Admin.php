<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            ->join('status', 'transaksi.id_status', '=', 'status.id_status')->orderBy('tgl_masuk')->limit(10)->get();
        $banyak_member = DB::table('users')->where('role', '=', 2)->count();
        $banyak_transaksi = DB::table('transaksi')->count();
        return view('admin.index', compact('user', 'transaksi_terbaru', 'banyak_member', 'banyak_transaksi'));
    }

    public function inputTransaksi()
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        return view('admin.input_transaksi', compact('user'));
    }

    public function riwayatTransaksi()
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        return view('admin.riwayat_transaksi', compact('user'));
    }

    public function harga()
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        return view('admin.harga', compact('user'));
    }

    public function members()
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        return view('admin.members', compact('user'));
    }

    public function saran()
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        return view('admin.saran', compact('user'));
    }

    public function laporan()
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        return view('admin.laporan', compact('user'));
    }
}
