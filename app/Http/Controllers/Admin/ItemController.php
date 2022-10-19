<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Fungsi untuk menambah barang baru
     */
    public function store(Request $request)
    {
        $item = new Item([
            'name' => ucfirst($request->input('barang'))
        ]);
        $item->save();

        return redirect('admin/harga')->with('success', 'Barang baru berhasil ditambah!');
    }
}
