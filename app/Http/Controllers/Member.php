<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function index(Request $request)
    {
        $user = DB::table('users')->where('email', '=', $this->logged_email)->first();
        $data['name'] = $user->name;
        $data['id'] = $user->id;
        $data['email'] = $this->logged_email;
        return view('member.index', $data);
    }

    public function harga()
    {
        $user = DB::table('users')->where('email', '=', $this->logged_email)->first();
        $data['name'] = $user->name;
        $data['id'] = $user->id;
        $data['email'] = $this->logged_email;
        return view('member.harga', $data);
    }

    public function riwayatTransaksi()
    {
        $user = DB::table('users')->where('email', '=', $this->logged_email)->first();
        $data['name'] = $user->name;
        $data['id'] = $user->id;
        $data['email'] = $this->logged_email;
        return view('member.riwayat', $data);
    }

    public function poin()
    {
        $user = DB::table('users')->where('email', '=', $this->logged_email)->first();
        $data['name'] = $user->name;
        $data['id'] = $user->id;
        $data['email'] = $this->logged_email;
        return view('member.poin', $data);
    }

    public function edit()
    {
        $user = DB::table('users')->where('email', '=', $this->logged_email)->first();
        $data['name'] = $user->name;
        $data['id'] = $user->id;
        $data['email'] = $this->logged_email;
        return view('member.edit', $data);
    }

    public function saranKomplain()
    {
        $user = DB::table('users')->where('email', '=', $this->logged_email)->first();
        $data['name'] = $user->name;
        $data['id'] = $user->id;
        $data['email'] = $this->logged_email;
        return view('member.saran', $data);
    }
}
