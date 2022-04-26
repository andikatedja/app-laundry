<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use App\User;

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

        if (!User::where('email', '=', $email)->exists()) {
            return redirect('login')->with('error', Lang::get('auth.error_email_password'));
        }

        // if (!DB::table('users')->where('email', '=', $email)->exists()) {
        //     return redirect('login')->with('error', Lang::get('auth.error_email_password'));
        // }

        $user = User::where('email', '=', $email)->first();

        if (Hash::check($password, $user->password)) {
            if ($user->role == '1') {

                $request->session()->put('login', $email);

                if ($request->has('remember')) {
                    return redirect('admin')
                        ->withCookie('login', $email, 10080)
                        ->withCookie('login_key', hash('sha256', env('COOKIE_SECRET_KEY', 'DefaultKey')), 10080);
                }

                return redirect('admin');
            } else {

                $request->session()->put('login', $email);

                if ($request->has('remember')) {
                    return redirect('member')
                        ->withCookie('login', $email, 10080)
                        ->withCookie('login_key', hash('sha256', env('COOKIE_SECRET_KEY', 'DefaultKey')), 10080);
                }

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
        if (User::where('email', '=', $request->input('email'))->exists()) {
            return redirect('register')->with('error', 'Email sudah terdaftar, harap mendaftarkan email yang lain.');
        }

        // if (Auth_model::isEmailExist($request->input('email'))) {
        //     return redirect('register')->with('error', 'Email sudah terdaftar, harap mendaftarkan email yang lain.');
        // }

        $hash_password = Hash::make($request->input('password'));

        $user = new User([
            'email' => $request->input('email'),
            'password' => $hash_password,
            'name' => $request->input('name')
        ]);

        $user->save();

        // Auth_model::insertNewMember($data);

        return redirect('login')->with('success', Lang::get('auth.register_success'));
    }

    /*
    | Fungsi untuk melakukan proses logout
    */
    public function logout(Request $request)
    {
        $request->session()->forget('login');
        $request->session()->flush();
        Cookie::forget('login');
        return redirect('login')
            ->with('success', Lang::get('auth.logout_success'))
            ->withCookie(Cookie::forget('login'))
            ->withCookie(Cookie::forget('login_key'));
    }
}
