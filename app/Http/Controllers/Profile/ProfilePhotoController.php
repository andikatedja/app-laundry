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
     * @return RedirectResponse
     */
    public function destroy(): RedirectResponse
    {
        $user = Auth::user();
        $user->profile_picture = 'default.jpg';
        $user->save();

        return redirect('profile')->with('success', 'Foto profil berhasil direset');
    }
}
