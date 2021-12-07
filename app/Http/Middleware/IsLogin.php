<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
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
        if ( $request->hasCookie('login') && $request->hasCookie('login_key')) {
            $email = $request->cookie('login'); //Email
            $secretKey = $request->cookie('login_key');

            if ($secretKey === hash('sha256', env('COOKIE_SECRET_KEY', 'DefaultKey'))) {
                $request->session()->put('login', $email);
            }
        }

        switch ($request->segment(1)) {
            case 'login':
                if (!$request->session()->has('login')) {
                    return $next($request);
                }

                $email_login = $request->session()->get('login');
                $user_login = DB::table('users')->where('email', '=', $email_login)->first();

                if ($user_login->role == '1') {
                    return redirect('admin');
                } else {
                    return redirect('member');
                }
                break;

            case 'admin':
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
                break;

            case 'member':
                if (!$request->session()->has('login')) {
                    return redirect('login')->with('error', Lang::get('auth.please_login'));
                }

                $userSession = $request->session()->get('login');
                $user = DB::table('users')->where('email', '=', $userSession)->first();

                if ($user->role == '2') {
                    return $next($request);
                } else {
                    return redirect('admin');
                }
                break;

            default:
                return $next($request);
                break;
        }
    }
}
