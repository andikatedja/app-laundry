<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterAdminRequest;
use App\Http\Requests\Auth\RegisterMemberRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Method to show register view
     *
     * @return void
     */
    public function show(): View
    {
        return view('auth.register');
    }

    /**
     * Method to register a new user
     *
     * @param RegisterMemberRequest $request
     * @return RedirectResponse
     */
    public function register(RegisterMemberRequest $request): RedirectResponse
    {
        User::create($request->safe()->all());

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
     * @param RegisterAdminRequest $request
     * @return RedirectResponse
     */
    public function registerAdmin(RegisterAdminRequest $request): RedirectResponse
    {
        // Check if secret key is the same
        if ($request->input('secret') != env('REGISTER_ADMIN_SECRET_KEY', 'Secret123')) {
            return redirect()->route('register.admin')->with('error', 'Secret key salah.');
        }

        $user = new User(
            $request->safe()->all()
        );
        $user->role = 1;
        $user->saveOrFail();

        return redirect()->route('login.show')->with('success', Lang::get('auth.register_success'));
    }
}
