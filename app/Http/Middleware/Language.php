<?php

namespace App\Http\Middleware;

use Closure;
use App;

class Language
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
        if (!$request->session()->has('locale')) {
            $request->session()->put('locale', config('app.locale'));
        }
        App::setLocale($request->session()->get('locale'));
        return $next($request);
    }
}
