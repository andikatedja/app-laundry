<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\PriceList;
use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PriceListController extends Controller
{
    /**
     * Fungsi untuk menampilkan halaman daftar harga
     */
    public function index()
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
     * Fungsi untuk mengambil harga untuk ajax
     */
    public function show(PriceList $priceList)
    {
        return response()->json($priceList);
    }

    /**
     * Fungsi untuk menambah harga baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'harga' => 'required'
        ]);

        if (PriceList::where([
            'item_id' => $request->input('barang'),
            'category_id' => $request->input('kategori'),
            'service_id' => $request->input('servis')
        ])->exists()) {
            return redirect('admin/harga')->with('error', 'Harga tidak dapat ditambah karena sudah tersedia!');
        }

        $priceList = new PriceList([
            'item_id' => $request->input('barang'),
            'category_id' => $request->input('kategori'),
            'service_id' => $request->input('servis'),
            'price' => $request->input('harga')
        ]);
        $priceList->save();

        return redirect('admin/harga')->with('success', 'Harga berhasil ditambahkan!');
    }

    /**
     * Fungsi untuk mengubah harga
     */
    public function update(PriceList $priceList, Request $request)
    {
        $priceList->price = $request->input('harga');
        $priceList->save();

        return redirect('admin/harga')->with('success', 'Harga berhasil diubah!');
    }
}
