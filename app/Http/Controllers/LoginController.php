<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use App\User;

class LoginController extends Controller
{
    /**
     * Fungsi untuk menampilkan halaman login
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Fungsi untuk melakukan proses login
     */
    public function auth(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $remember = false;

        if ($request->has('remember')) {
            $remember = true;
        }

        if (Auth::attempt($credentials, $remember)) {
            if (Auth::user()->role == 1) {
                return redirect('admin');
            } else {
                return redirect('member');
            }
        }
        // $email = $request->input('email');
        // $password = $request->input('password');

        // if (!User::where('email', '=', $email)->exists()) {
        //     return redirect('login')->with('error', Lang::get('auth.error_email_password'));
        // }

        // $user = User::where('email', '=', $email)->first();

        // if (Hash::check($password, $user->password)) {
        //     if ($user->role == '1') {

        //         $request->session()->regenerate();

        //         // $request->session()->put('login', $email);

        //         if ($request->has('remember')) {
        //             return redirect('admin')
        //                 ->withCookie('login', $email, 10080)
        //                 ->withCookie('login_key', hash('sha256', env('COOKIE_SECRET_KEY', 'DefaultKey')), 10080);
        //         }

        //         return redirect('admin');
        //     } else {

        //         $request->session()->put('login', $email);

        //         if ($request->has('remember')) {
        //             return redirect('member')
        //                 ->withCookie('login', $email, 10080)
        //                 ->withCookie('login_key', hash('sha256', env('COOKIE_SECRET_KEY', 'DefaultKey')), 10080);
        //         }

        //         return redirect('member');
        //     }
        // } else {
        //     return redirect('login')->with('error', Lang::get('auth.error_email_password'));
        // }
    }

    /**
     * Fungsi untuk menampilkan halaman register
     */
    public function registerView()
    {
        return view('auth.register');
    }

    /**
     * Fungsi untuk melakukan proses register
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

        $hash_password = Hash::make($request->input('password'));

        $user = new User([
            'email' => $request->input('email'),
            'password' => $hash_password,
            'name' => $request->input('name')
        ]);

        $user->save();

        return redirect('login')->with('success', Lang::get('auth.register_success'));
    }


    /**
     * Fungsi untuk menampilkan view register admin
     */
    public function registerAdminView()
    {
        return view('auth.registerAdmin');
    }

    /**
     * Fungsi untuk register admin
     */
    public function registerAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'secret' => 'required'
        ]);

        // Cek apakah email sudah terdaftar
        if (User::where('email', '=', $request->input('email'))->exists()) {
            return redirect('register-admin')->with('error', 'Email sudah terdaftar, harap mendaftarkan email yang lain.');
        }

        // Cek apakah secret key sama
        if ($request->input('secret') != env('REGISTER_ADMIN_SECRET_KEY', 'Secret123')) {
            return redirect('register-admin')->with('error', 'Secret key salah.');
        }

        $hash_password = Hash::make($request->input('password'));

        $user = new User([
            'email' => $request->input('email'),
            'password' => $hash_password,
            'role' => 1,
            'name' => $request->input('name')
        ]);

        $user->save();

        return redirect('login')->with('success', Lang::get('auth.register_success'));
    }

    /**
     * Fungsi untuk melakukan proses logout
     */
    public function logout(Request $request)
    {
        // $request->session()->forget('login');
        // $request->session()->flush();
        // Cookie::forget('login');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login')
            ->with('success', Lang::get('auth.logout_success'));
        // ->withCookie(Cookie::forget('login'))
        // ->withCookie(Cookie::forget('login_key'));
    }
}
