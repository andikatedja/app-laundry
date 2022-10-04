<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    /**
     * Method to show user profile
     *
     * @return View
     */
    public function show(): View
    {
        $user = Auth::user();

        return view('user.profile', compact('user'));
    }

    /**
     * Method to process user profile edit
     *
     * @param UpdateProfileRequest $request
     * @return RedirectResponse
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = User::where('email', $request->user()->email)->first();

        $user->fill($request->safe(['profile_picture']));
        $user->save();

        return redirect('profile')->with('success', 'Profil berhasil diedit!');
    }

    /**
     * Method to reset user profile picture
     *
     * @return RedirectResponse
     */
    public function resetPhoto(): RedirectResponse
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
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current-password' => 'required',
            'password' => 'required|min:8|confirmed',
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
