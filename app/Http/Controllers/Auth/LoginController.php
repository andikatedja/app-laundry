<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    /**
     * Method to show login view
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show(): View
    {
        return view('auth.login');
    }

    /**
     * Method to authenticate login
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login.show')
                    ->with('error', Lang::get('auth.error_email_password'))
                    ->withInput();
            }

            if ($user->role == Role::Admin) {
                return redirect()->route('admin.index');
            } else {
                return redirect()->route('member.index');
            }
        }

        return redirect()->route('login.show')
            ->with('error', Lang::get('auth.error_email_password'))
            ->withInput();
    }

    /**
     * Method to logout user from session
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.show')
            ->with('success', Lang::get('auth.logout_success'));
    }
}
