<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;
use App\Auth_model;

class Auth extends Controller
{
    /*
    | Fungsi untuk menampilkan halaman login
    */
    public function index()
    {
        return view('auth.login');
    }

    /*
    | Fungsi untuk melakukan proses login
    */
    public function auth(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        if (!DB::table('users')->where('email', '=', $email)->exists()) {
            return redirect('login')->with('error', Lang::get('auth.error_email_password'));
        }

        $user = DB::table('users')->where('email', '=', $email)->first();

        if (Hash::check($password, $user->password)) {
            if ($user->role == '1') {
                $request->session()->put('login', $email);
                return redirect('admin');
            } else {
                $request->session()->put('login', $email);
                return redirect('member');
            }
        } else {
            return redirect('login')->with('error', Lang::get('auth.error_email_password'));
        }
    }

    /*
    | Fungsi untuk menampilkan halaman register
    */
    public function registerView()
    {
        return view('auth.register');
    }

    /*
    | Fungsi untuk melakukan proses register
    */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6'
        ]);

        // Cek apakah email sudah terdaftar
        if (Auth_model::isEmailExist($request->input('email'))) {
            return redirect('register')->with('error', 'Email sudah terdaftar, harap mendaftarkan email yang lain.');
        }

        $hash_password = Hash::make($request->input('password'));

        $data = [
            'email' => $request->input('email'),
            'password' => $hash_password,
            'nama' => $request->input('name')
        ];

        Auth_model::insertNewMember($data);

        return redirect('login')->with('success', Lang::get('auth.register_success'));
    }

    /*
    | Fungsi untuk melakukan proses logout
    */
    public function logout(Request $request)
    {
        $request->session()->forget('login');
        $request->session()->flush();
        return redirect('login')->with('success', Lang::get('auth.logout_success'));
    }
}
