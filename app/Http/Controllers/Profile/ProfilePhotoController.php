<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilePhotoController extends Controller
{
    /**
     * Method to reset user profile picture
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $user->profile_picture = 'default.jpg';
        $user->save();

        return redirect()->route('profile.index')
            ->with('success', 'Foto profil berhasil direset');
    }
}
