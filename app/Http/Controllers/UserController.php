<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    /**
     * Method to show user profile
     *
     * @return View
     */
    public function index(): View
    {
        $user = Auth::user();

        return view('user.profile', compact('user'));
    }

    /**
     * Method to process user profile edit
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function editprofil(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'jenis_kelamin' => 'required',
            'alamat' => 'required',
            'telp' => 'required'
        ]);

        $user = User::where('email', '=', Auth::user()->email)->first();

        if ($request->hasFile('image')) {
            // Check if previous photo is not default
            if ($user->profile_picture != 'default.jpg') {
                // Delete old file
                File::delete(public_path('img/profile/' . $user->profile_picture));
            }

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
     * Method to reset user profile picture
     *
     * @return RedirectResponse
     */
    public function resetfoto(): RedirectResponse
    {
        $user = User::where('email', '=', Auth::user()->email)->first();
        $user->profile_picture = 'default.jpg';
        $user->save();

        return redirect('profile')->with('success', 'Foto profil berhasil direset');
    }

    /**
     * Method to update user's password
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function editpassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current-password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('email', '=', Auth::user()->email)->first();

        // Check if current password is the same
        if (!Hash::check($request->input('current-password'), $user->password)) {
            return redirect('profile')->with('error', 'Kata sandi sekarang salah!');
        }

        $passwordHash = Hash::make($request->input('password'));

        $user->password = $passwordHash;
        $user->save();

        return redirect('profile')->with('success', 'Kata sandi berhasil diubah');
    }
}
