<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class IsLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        switch ($request->segment(1)) {
            case 'login':
                if (!Auth::check()) {
                    return $next($request);
                }

                if (Auth::user()->role == Role::Admin) {
                    return redirect('admin');
                } else {
                    return redirect('member');
                }

            case 'admin':
                if (!Auth::check()) {
                    return redirect('login')->with('error', Lang::get('auth.please_login'));
                }

                if (Auth::user()->role == Role::Admin) {
                    return $next($request);
                } else {
                    return redirect('member');
                }

            case 'member':
                if (!Auth::check()) {
                    return redirect('login')->with('error', Lang::get('auth.please_login'));
                }

                if (Auth::user()->role == Role::Member) {
                    return $next($request);
                } else {
                    return redirect('admin');
                }

            default:
                return $next($request);
        }
    }
}
