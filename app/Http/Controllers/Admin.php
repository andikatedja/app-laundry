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

    public function index(Request $request)
    {
        $user = DB::table('users')->join('users_info', 'users.id', '=', 'users_info.id_user')
            ->select('users_info.*')->where('email', '=', $this->logged_email)->first();
        return view('admin.index', compact('user'));
    }
}
