<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class IsAdmin
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
        if (!$request->session()->has('login')) {
            return redirect('login')->with('error', Lang::get('auth.please_login'));
        }
        $userSession = $request->session()->get('login');
        $user = DB::table('users')->where('email', '=', $userSession)->first();
        if ($user->role == '1') {
            return $next($request);
        } else {
            return redirect('member');
        }
        return $next($request);
    }
}
