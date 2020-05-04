<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

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
        if (!$request->session()->has('login')) {
            return $next($request);
        } else {
            $email_login = $request->session()->get('login');
            $user_login = DB::table('users')->where('email', '=', $email_login)->first();
            if ($user_login->role == '1') {
                return redirect('admin');
            } else {
                return redirect('member');
            }
        }

        return $next($request);
    }
}
