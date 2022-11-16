<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilePasswordController extends Controller
{
    /**
     * Method to update user's password
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        // Check if current password is the same
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect('profile')->with('error', 'Kata sandi sekarang salah!');
        }

        $user->fill($request->only(['password']));
        $user->saveOrFail();

        return redirect()->route('profile.index')
            ->with('success', 'Kata sandi berhasil diubah');
    }
}
