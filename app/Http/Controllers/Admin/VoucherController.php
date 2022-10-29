<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    /**
     * Show voucher page
     *
     * @return View
     */
    public function index(): View
    {
        $user = Auth::user();
        $vouchers = Voucher::all();

        return view('admin.voucher', compact('user', 'vouchers'));
    }

    /**
     * Add new voucher to database
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Cek apakah potongan ada yang sama di database
        if (Voucher::where('discount_value', $request->input('potongan'))->exists()) {
            return redirect()->route('admin.vouchers.index')
                ->with('error', 'Voucher potongan ' . $request->input('potongan') . ' sudah ada');
        }

        // Masukkan potongan ke dalam tabel vouchers
        $voucher = new Voucher([
            'name' => 'Potongan ' . number_format($request->input('potongan'), 0, ',', '.'),
            'discount_value' => $request->input('potongan'),
            'point_need' => $request->input('poin'),
            'description' => 'Mendapatkan potongan harga ' . number_format($request->input('potongan'), 0, ',', '.') . ' dari total transaksi',
            'active_status' => 1
        ]);
        $voucher->save();

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher baru berhasil ditambah!');
    }

    /**
     * Update voucher status
     *
     * @param Voucher $voucher
     * @return JsonResponse
     */
    public function update(Voucher $voucher): JsonResponse
    {
        // Jika 1 maka ubah ke 0
        if ($voucher->active_status == 1) {
            $voucher->active_status = 0;
            $voucher->save();
        } else {
            // Ubah ke 1
            $voucher->active_status = 1;
            $voucher->save();
        }

        return response()->json();
    }
}
