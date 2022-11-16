<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Add new service to database
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'service_name' => ['required'],
        ]);

        $service = new Service([
            'name' => ucfirst($request->input('service_name'))
        ]);
        $service->save();

        return redirect()->route('admin.price-lists.index')
            ->with('success', 'Servis baru berhasil ditambah!');
    }
}
