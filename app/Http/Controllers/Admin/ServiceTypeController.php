<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    /**
     * Fungsi untuk mengambil biaya service type
     */
    public function show(ServiceType $serviceType)
    {
        return response()->json($serviceType);
    }

    /**
     * Fungsi untuk mengubah service type
     */
    public function update(ServiceType $serviceType, Request $request)
    {
        $serviceType->cost = $request->input('cost');
        $serviceType->save();

        return redirect('admin/harga')->with('success', 'Biaya service berhasil diubah!');
    }
}
