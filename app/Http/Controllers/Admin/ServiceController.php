<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Fungsi untuk menambah servis baru
     */
    public function store(Request $request)
    {
        $service = new Service([
            'name' => ucfirst($request->input('servis'))
        ]);
        $service->save();

        return redirect('admin/harga')->with('success', 'Servis baru berhasil ditambah!');
    }
}
