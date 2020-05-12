<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;

class Auth extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

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
                return redirect()->route('admin');
            } else {
                $request->session()->put('login', $email);
                return redirect()->route('member');
            }
        } else {
            return redirect('login')->with('error', Lang::get('auth.error_email_password'));
        }
    }

    public function registerView()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6'
        ]);
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        // $password2 = $request->input('password2');

        // if ($password != $password2) {
        //     return redirect('register')->with('error', 'Password and confirm password not match');
        // }

        $hash_password = Hash::make($password);

        $user_id = DB::table('users')->insertGetId([
            'id' => null,
            'email' => $email,
            'password' => $hash_password,
            'role' => '2'
        ]);

        DB::table('users_info')->insert([
            'id_user' => $user_id,
            'nama' => $name,
            'jenis_kelamin' => '',
            'alamat' => '',
            'no_telp' => '',
            'profil' => 'default.jpg',
            'poin' => 0
        ]);

        return redirect('register')->with('success', Lang::get('auth.register_success'));
    }

    public function logout(Request $request)
    {
        $request->session()->forget('login');
        $request->session()->flush();
        return redirect('login')->with('success', Lang::get('auth.logout_success'));
    }
}
