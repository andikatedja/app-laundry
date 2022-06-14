<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $logged_email;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->logged_email = session()->get('login');
            return $next($request);
        });
    }

    /**
     * Fungsi untuk menampilkan halaman edit profil
     */
    public function index()
    {
        $user = User::where('email', '=', $this->logged_email)->first();
        return view('user.profile', compact('user'));
    }

    /**
     * Fungsi untuk melakukan edit profil
     */
    public function editprofil(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'jenis_kelamin' => 'required',
            'alamat' => 'required',
            'telp' => 'required'
        ]);

        $user = User::where('email', '=', $this->logged_email)->first();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = $user->id . '.' . $extension;
            $path = public_path('img/profile');
            $image->move($path, $filename);
        }

        $user->name = $request->input('name');
        $user->gender = $request->input('jenis_kelamin');
        $user->address = $request->input('alamat');
        $user->phone_number = $request->input('telp');
        $user->profile_picture = $request->hasFile('image') ? $filename : $user->profile_picture;
        $user->save();

        return redirect('profile')->with('success', 'Profil berhasil diedit!');
    }

    /**
     * Fungsi untuk reset foto
     */
    public function resetfoto()
    {
        $user = User::where('email', '=', $this->logged_email)->first();
        $user->profile_picture = 'default.jpg';
        $user->save();
        return redirect('profile')->with('success', 'Foto profil berhasil direset');
    }

    /**
     * Fungsi untuk melakukan update password
     */
    public function editpassword(Request $request)
    {
        $request->validate([
            'current-password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);
        $user = User::where('email', '=', $this->logged_email)->first();

        //Cek apakah password lama sama
        if (!Hash::check($request->input('current-password'), $user->password)) {
            return redirect('profile')->with('error', 'Kata sandi sekarang salah!');
        }

        $password_hash = Hash::make($request->input('password'));

        $user->password = $password_hash;
        $user->save();
        return redirect('profile')->with('success', 'Kata sandi berhasil diubah');
    }
}
