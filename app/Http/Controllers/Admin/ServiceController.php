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
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $service = new Service([
            'name' => ucfirst($request->input('servis'))
        ]);
        $service->save();

        return redirect()->route('admin.price-lists.index')
            ->with('success', 'Servis baru berhasil ditambah!');
    }
}
