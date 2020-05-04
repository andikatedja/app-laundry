<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Admin extends Controller
{
    private $folder = 'admin';
    public function index(Request $request)
    {
        $email = $request->session()->get('login');
        $user_login = DB::table('users')->where('email', '=', $email)->first();

        $name = $user_login->name;
        return view($this->folder . '.index', compact('name'));
    }
}
