<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\PriceList;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TransactionSessionController extends Controller
{
    /**
     * Method to add new transaction to session
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $itemId = $request->input('item');
        $serviceId = $request->input('service');
        $categoryId = $request->input('category');
        $memberId = $request->input('member-id');
        $quantity = $request->input('quantity');

        // Check if price list exist in database
        if (!PriceList::where([
            'item_id' => $itemId,
            'category_id' => $categoryId,
            'service_id' => $serviceId
        ])->exists()) {
            return redirect()->route('admin.transactions.create')->with('error', 'Harga tidak ditemukan!');
        }

        // Check if member exist
        if ($memberId != null && !User::where('id', $memberId)->where('role', 2)->exists()) {
            return redirect()->route('admin.transactions.create')->with('error', 'Member tidak ditemukan!');
        }

        // Get price list's price from database
        $price = PriceList::where([
            'item_id' => $itemId,
            'category_id' => $categoryId,
            'service_id' => $serviceId
        ])->first()->price;

        // Calculate sub total
        $subTotal = $price * $quantity;

        // Get item name, service name, and category name based on id
        $itemName = Item::where('id', $itemId)->first()->name;
        $serviceName = Service::where('id', $serviceId)->first()->name;
        $categoryName = Category::where('id', $categoryId)->first()->name;

        // make new transaction row to store in session
        $rowId = md5($memberId . serialize($itemId) . serialize($serviceId) . serialize($categoryId));
        $data = [
            $rowId => [
                'itemId' => $itemId,
                'itemName' => $itemName,
                'categoryId' => $categoryId,
                'categoryName' => $categoryName,
                'serviceId' => $serviceId,
                'serviceName' => $serviceName,
                'quantity' => $quantity,
                'subTotal' => $subTotal,
                'rowId' => $rowId
            ]
        ];

        // Check if there is no transaction session, create new session
        if (!$request->session()->has('transaction') && !$request->session()->has('memberIdTransaction')) {
            $request->session()->put('transaction', $data);
            $request->session()->put('memberIdTransaction', $memberId);
        } else {
            $exist = 0;
            $sessionTransaction = $request->session()->get('transaction');

            // Check if there is a same transaction. If exist, just increment the quantity and subtotal
            foreach ($sessionTransaction as &$transaction) {
                if ($transaction['itemId'] == $itemId && $transaction['categoryId'] == $categoryId && $transaction['serviceId'] == $serviceId) {
                    $transaction['quantity'] += $quantity;
                    $transaction['subTotal'] += $subTotal;
                    $exist++;
                }
            }

            // check if there is no same transaction, then insert new transaction to current transaction session
            if ($exist == 0) {
                $newSessionTransaction = array_merge_recursive($sessionTransaction, $data);
                $request->session()->put('transaction', $newSessionTransaction);
            } else {
                $request->session()->put('transaction', $sessionTransaction);
            }
        }

        return redirect()->route('admin.transactions.create');
    }

    /**
     * Method for delete current transaction in session
     *
     * @param mixed $row_id
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(string $rowId, Request $request): RedirectResponse
    {
        $sessionTransaction = $request->session()->get('transaction');
        unset($sessionTransaction[$rowId]);

        // Check if after unset, the transaction session is empty ([]), then destroy all transaction session
        if ($sessionTransaction == []) {
            $request->session()->forget('transaction');
            $request->session()->forget('memberIdTransaction');
            return redirect()->route('admin.transactions.create');
        } else {
            $request->session()->put('transaction', $sessionTransaction);
        }

        return redirect()->route('admin.transactions.create');
    }
}
