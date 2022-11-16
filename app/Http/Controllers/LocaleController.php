<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(string $locale, Request $request): RedirectResponse
    {
        if (!in_array($locale, ['en', 'id'])) {
            abort(404);
        }

        $request->session()->put('locale', $locale);

        return redirect()->back();
    }
}
