<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class CheckRole
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
            return redirect('login');
        }
        $userSession = $request->session()->get('login');
        $user = DB::table('users')->where('email', '=', $userSession)->first();
        if ($user->role == '1') {
            if ($request->route('member')) {
                return redirect('admin');
            } else {
                return $next($request);
            }
        } else {
            return $next($request);
        }
        return $next($request);
    }
}
