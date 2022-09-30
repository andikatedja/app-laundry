<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Method to show login view
     *
     * @return void
     */
    public function show(): View
    {
        return view('auth.login');
    }

    /**
     * Method to authenticate login
     *
     * @param Request $request
     * @return void
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember') ? true : false;

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if (Auth::user()->role == 1) {
                return redirect('admin');
            } else {
                return redirect('member');
            }
        }

        return redirect('login')->with('error', Lang::get('auth.error_email_password'));
    }

    /**
     * Method to logout user from session
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.show')->with('success', Lang::get('auth.logout_success'));
    }
}
