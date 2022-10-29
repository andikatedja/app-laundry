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
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $item = new Item([
            'name' => ucfirst($request->input('barang'))
        ]);
        $item->save();

        return redirect()->route('admin.price-lists.index')
            ->with('success', 'Barang baru berhasil ditambah!');
    }
}
