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
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $user = Auth::user();

        $priceList = PriceList::with(['service', 'item'])->get();

        $satuan = $priceList->filter(function (PriceList $value, $key) {
            return $value->category_id == 1;
        })->all();

        $kiloan = $priceList->filter(function (PriceList $value, $key) {
            return $value->category_id == 2;
        })->all();

        $items = Item::all();
        $services = Service::all();
        $categories = Category::all();
        $serviceTypes = ServiceType::all();

        return view('admin.price_lists', compact(
            'user',
            'satuan',
            'kiloan',
            'items',
            'services',
            'categories',
            'serviceTypes',
        ));
    }

    /**
     * Get price list price
     *
     * @param  \App\Models\PriceList $priceList
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(PriceList $priceList): JsonResponse
    {
        return response()->json($priceList);
    }

    /**
     * Store new price list to database
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'price' => 'required'
        ]);

        if (PriceList::where([
            'item_id'     => $request->input('item'),
            'category_id' => $request->input('category'),
            'service_id'  => $request->input('service'),
        ])->exists()) {
            return redirect()->route('admin.price-lists.index')
                ->with('error', 'Harga tidak dapat ditambah karena sudah tersedia!');
        }

        $priceList = new PriceList([
            'item_id'     => $request->input('item'),
            'category_id' => $request->input('category'),
            'service_id'  => $request->input('service'),
            'price'       => $request->input('price'),
        ]);
        $priceList->save();

        return redirect()->route('admin.price-lists.index')
            ->with('success', 'Harga berhasil ditambahkan!');
    }

    /**
     * Change price list price
     *
     * @param  \App\Models\PriceList $priceList
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PriceList $priceList, Request $request): RedirectResponse
    {
        $priceList->price = $request->input('price');
        $priceList->save();

        return redirect()->route('admin.price-lists.index')
            ->with('success', 'Harga berhasil diubah!');
    }
}
