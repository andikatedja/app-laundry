<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\PriceList;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionSessionController extends Controller
{
    /**
     * Method to add new transaction to session
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $inputData = $request->validate([
            'item'      => ['required'],
            'service'   => ['required'],
            'category'  => ['required'],
            'member-id' => [Rule::requiredIf(!$request->session()->has('memberIdTransaction'))],
            'quantity'  => ['required'],
        ]);

        // Make sure input data member id is not empty
        $inputData['member-id'] = $inputData['member-id'] ?? $request->session()->get('memberIdTransaction');


        // Check if price list exist in database
        if (!PriceList::where([
            'item_id'     => $inputData['item'],
            'category_id' => $inputData['category'],
            'service_id'  => $inputData['service'],
        ])->exists()) {
            return redirect()->route('admin.transactions.create')->with('error', 'Harga tidak ditemukan!');
        }

        // Check if member exist
        $memberNotExist = !User::where('id', $inputData['member-id'])->where('role', Role::Member)->exists();

        if ($memberNotExist) {
            return redirect()->route('admin.transactions.create')->with('error', 'Member tidak ditemukan!');
        }

        // Get price list's price from database
        $price = PriceList::where([
            'item_id'     => $inputData['item'],
            'category_id' => $inputData['category'],
            'service_id'  => $inputData['service']
        ])->firstOrFail()->price;

        // Calculate sub total
        $subTotal = $price * $inputData['quantity'];

        // Get item name, service name, and category name based on id
        $itemName     = Item::where('id', $inputData['item'])->firstOrFail()->name;
        $serviceName  = Service::where('id', $inputData['service'])->firstOrFail()->name;
        $categoryName = Category::where('id', $inputData['category'])->firstOrFail()->name;

        // make new transaction row to store in session
        $rowId = md5($inputData['member-id'] . serialize($inputData['item']) . serialize($inputData['service']) . serialize($inputData['category']));
        $data = [
            $rowId => [
                'itemId'       => $inputData['item'],
                'itemName'     => $itemName,
                'categoryId'   => $inputData['category'],
                'categoryName' => $categoryName,
                'serviceId'    => $inputData['service'],
                'serviceName'  => $serviceName,
                'quantity'     => $inputData['quantity'],
                'subTotal'     => $subTotal,
                'rowId'        => $rowId
            ]
        ];

        // Check if there is no transaction session, create new session
        if (!$request->session()->has('transaction') && !$request->session()->has('memberIdTransaction')) {
            $request->session()->put('transaction', $data);
            $request->session()->put('memberIdTransaction', $inputData['member-id']);
        } else {
            $exist = 0;
            $sessionTransaction = $request->session()->get('transaction');

            // Check if there is a same transaction. If exist, just increment the quantity and subtotal
            foreach ($sessionTransaction as &$transaction) {
                if ($transaction['itemId'] == $inputData['item'] && $transaction['categoryId'] == $inputData['category'] && $transaction['serviceId'] == $inputData['service']) {
                    $transaction['quantity'] += $inputData['quantity'];
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
     * @param  string $rowId
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $rowId, Request $request): RedirectResponse
    {
        $sessionTransaction = $request->session()->get('transaction');
        unset($sessionTransaction[$rowId]);

        // Check if after unset, the transaction session is empty ([]), then destroy all transaction session
        if (blank($sessionTransaction)) {
            $request->session()->forget('transaction');
            $request->session()->forget('memberIdTransaction');
            return redirect()->route('admin.transactions.create');
        } else {
            $request->session()->put('transaction', $sessionTransaction);
        }

        return redirect()->route('admin.transactions.create');
    }
}
