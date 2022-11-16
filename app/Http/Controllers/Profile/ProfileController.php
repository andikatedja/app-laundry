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
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $user = Auth::user();

        return view('user.profile', compact('user'));
    }

    /**
     * Method to process user profile edit
     *
     * @param  \App\Http\Requests\Profile\UpdateProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $user->fill($request->safe()->all());
        $user->save();

        return redirect()->route('profile.index')
            ->with('success', 'Profil berhasil diedit!');
    }
}
