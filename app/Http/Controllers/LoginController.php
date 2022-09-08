<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    /**
     * Method to show login view
     *
     * @return void
     */
    public function index(): View
    {
        return view('auth.login');
    }

    /**
     * Method to authenticate login
     *
     * @param Request $request
     * @return void
     */
    public function auth(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $remember = false;

        if ($request->has('remember')) {
            $remember = true;
        }

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
     * Method to show register view
     *
     * @return void
     */
    public function registerView(): View
    {
        return view('auth.register');
    }

    /**
     * Method to register a new user
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6'
        ]);

        // Check if email already registered
        if (User::where('email', '=', $request->input('email'))->exists()) {
            return redirect('register')->with('error', 'Email sudah terdaftar, harap mendaftarkan email yang lain.');
        }

        $hashPassword = Hash::make($request->input('password'));

        $user = new User([
            'email' => $request->input('email'),
            'password' => $hashPassword,
            'name' => $request->input('name')
        ]);

        $user->save();

        return redirect('login')->with('success', Lang::get('auth.register_success'));
    }


    /**
     * Method to show register admin view
     *
     * @return View
     */
    public function registerAdminView(): View
    {
        return view('auth.registerAdmin');
    }

    /**
     * Method to add new admin-level user
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function registerAdmin(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'secret' => 'required'
        ]);

        // Check if email already registered
        if (User::where('email', '=', $request->input('email'))->exists()) {
            return redirect('register-admin')->with('error', 'Email sudah terdaftar, harap mendaftarkan email yang lain.');
        }

        // Check if secret key is the same
        if ($request->input('secret') != env('REGISTER_ADMIN_SECRET_KEY', 'Secret123')) {
            return redirect('register-admin')->with('error', 'Secret key salah.');
        }

        $hashPassword = Hash::make($request->input('password'));

        $user = new User([
            'email' => $request->input('email'),
            'password' => $hashPassword,
            'role' => 1,
            'name' => $request->input('name')
        ]);

        $user->save();

        return redirect('login')->with('success', Lang::get('auth.register_success'));
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

        return redirect('login')->with('success', Lang::get('auth.logout_success'));
    }
}
