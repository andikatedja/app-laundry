<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    /**
     * Fungsi untuk menampilkan halaman daftar member
     */
    public function index()
    {
        $user = Auth::user();
        $members = User::where('role', 2)->get();

        return view('admin.members', compact('user', 'members'));
    }
}
