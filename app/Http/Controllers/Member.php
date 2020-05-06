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
        return view('member.index', compact('user'));
    }

    public function harga()
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        return view('member.harga', compact('user'));
    }

    public function riwayatTransaksi()
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        return view('member.riwayat', compact('user'));
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
        return view('member.saran', compact('user'));
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
            'password_now' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);
        $db_password = DB::table('users')->where('email', '=', $this->logged_email)->pluck('password');

        //Cek apakah password lama sama
        if (!Hash::check($request->input('password_now'), $db_password[0])) {
            return redirect('member/edit')->with('error', 'Kata sandi sekarang salah!');
        }

        $password_hash = Hash::make($request->input('password'));
        DB::table('users')->where('email', '=', $this->logged_email)->update([
            'password' => $password_hash
        ]);
        return redirect('member/edit')->with('success', 'Kata sandi berhasil diubah');
    }
}
