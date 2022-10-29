<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\PriceList;
use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PriceListController extends Controller
{
    /**
     * Display price lists view
     *
     * @return View
     */
    public function index(): View
    {
        $user = Auth::user();
        $satuan = PriceList::where('category_id', 1)->get();
        $kiloan = PriceList::where('category_id', 2)->get();
        $items = Item::all();
        $services = Service::all();
        $categories = Category::all();
        $serviceTypes = ServiceType::all();

        return view('admin.price_lists', compact('user', 'satuan', 'kiloan', 'items', 'services', 'categories', 'serviceTypes'));
    }

    /**
     * Get price list price
     *
     * @param PriceList $priceList
     * @return JsonResponse
     */
    public function show(PriceList $priceList): JsonResponse
    {
        return response()->json($priceList);
    }

    /**
     * Store new price list to database
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'harga' => 'required'
        ]);

        if (PriceList::where([
            'item_id' => $request->input('barang'),
            'category_id' => $request->input('kategori'),
            'service_id' => $request->input('servis')
        ])->exists()) {
            return redirect()->route('admin.price-lists.index')
                ->with('error', 'Harga tidak dapat ditambah karena sudah tersedia!');
        }

        $priceList = new PriceList([
            'item_id' => $request->input('barang'),
            'category_id' => $request->input('kategori'),
            'service_id' => $request->input('servis'),
            'price' => $request->input('harga')
        ]);
        $priceList->save();

        return redirect()->route('admin.price-lists.index')
            ->with('success', 'Harga berhasil ditambahkan!');
    }

    /**
     * Change price list price
     *
     * @param PriceList $priceList
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(PriceList $priceList, Request $request): RedirectResponse
    {
        $priceList->price = $request->input('harga');
        $priceList->save();

        return redirect()->route('admin.price-lists.index')
            ->with('success', 'Harga berhasil diubah!');
    }
}
