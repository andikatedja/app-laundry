<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterAdminRequest;
use App\Http\Requests\Auth\RegisterMemberRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Lang;

class RegisterController extends Controller
{
    /**
     * Method to show register view
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show(): View
    {
        return view('auth.register');
    }

    /**
     * Method to register a new user
     *
     * @param  \App\Http\Requests\Auth\RegisterMemberRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterMemberRequest $request): RedirectResponse
    {
        User::create($request->safe()->all());

        return redirect()->route('login.show')
            ->with('success', Lang::get('auth.register_success'));
    }


    /**
     * Method to show register admin view
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function registerAdminView(): View
    {
        return view('auth.registerAdmin');
    }

    /**
     * Method to add new admin-level user
     *
     * @param  \App\Http\Requests\Auth\RegisterAdminRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerAdmin(RegisterAdminRequest $request): RedirectResponse
    {
        // Check if secret key is the same
        if ($request->input('secret') != env('REGISTER_ADMIN_SECRET_KEY', 'Secret123')) {
            return redirect()->route('register.admin')
                ->with('error', 'Secret key salah.');
        }

        $user = new User(
            $request->safe()->all()
        );
        $user->role = Role::Admin;
        $user->saveOrFail();

        return redirect()->route('login.show')
            ->with('success', Lang::get('auth.register_success'));
    }
}
