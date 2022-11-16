<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Add new item to database
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'item_name' => ['required'],
        ]);

        $item = new Item([
            'name' => ucfirst($request->input('item_name'))
        ]);
        $item->save();

        return redirect()->route('admin.price-lists.index')
            ->with('success', 'Barang baru berhasil ditambah!');
    }
}
