<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    /**
     * Get service type data
     *
     * @param ServiceType $serviceType
     * @return JsonResponse
     */
    public function show(ServiceType $serviceType): JsonResponse
    {
        return response()->json($serviceType);
    }

    /**
     * Update service type cost
     *
     * @param ServiceType $serviceType
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(ServiceType $serviceType, Request $request): RedirectResponse
    {
        $serviceType->cost = $request->input('cost');
        $serviceType->save();

        return redirect()->route('admin.price-lists.index')
            ->with('success', 'Biaya service berhasil diubah!');
    }
}
