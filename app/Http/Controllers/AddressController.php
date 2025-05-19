<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Address;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    // 住所変更画面表示
    public function showChangeForm($item_id)
    {
        $item = Item::findOrFail($item_id);
        $address = Address::where('user_id', Auth::id())->first();

        return view('purchases.edit', compact('item', 'address'))
            ->with('item_id', $item_id);

    }

    // 住所情報を保存 or 更新
    public function updateAddress(AddressRequest $request, $item_id)
    {
        Address::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'postal_code' => $request->postal_code,
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
            ]
        );

        return redirect()->route('checkout', ['item_id' => $item_id])
            ->with('success', '配送先住所を更新しました');
    }
}
