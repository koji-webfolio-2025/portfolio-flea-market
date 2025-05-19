<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Address;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function checkout($item_id)
    {
        $item = Item::findOrFail($item_id);
        $address = Auth::user()->address;

        if (!$address) {
            return redirect("/purchase/address/{$item_id}")->with('error', '住所情報を先に登録してください。');
        }

        return view('checkout', compact('item', 'address'));
    }

    public function payment(PurchaseRequest $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $item = Item::findOrFail($request->item_id);

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->title,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/payment/success') . '?session_id={CHECKOUT_SESSION_ID}&item_id=' . $item->id,
            'cancel_url' => url('/') . '?cancel=true',
        ]);

        return redirect($session->url);
    }

    public function paymentSuccess(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session_id = $request->query('session_id');
        $item_id = $request->query('item_id');

        $session = StripeSession::retrieve($session_id);

        $address = Address::where('user_id', Auth::id())->first();

        // pendingレコードを探して completed に更新！
        $purchase = Purchase::where('user_id', Auth::id())
            ->where('item_id', $item_id)
            ->where('status', 'pending')
            ->first();

        if ($purchase) {
            $purchase->update([
                'status' => 'completed',
                'shipping_postal_code' => $address->postal_code ?? '',
                'shipping_address_line1' => $address->address_line1 ?? '',
                'shipping_address_line2' => $address->address_line2 ?? '',
            ]);
        } else {
            // もしpendingが無かった場合は念のため作成
            Purchase::create([
                'user_id' => Auth::id(),
                'item_id' => $item_id,
                'payment_method' => 'card',
                'status' => 'completed',
                'shipping_postal_code' => $address->postal_code ?? '',
                'shipping_address_line1' => $address->address_line1 ?? '',
                'shipping_address_line2' => $address->address_line2 ?? '',
            ]);
        }

        // アイテムを売却済みに
        $item = Item::findOrFail($item_id);
        $item->is_sold = true;
        $item->save();

        return view('payment_success');
    }
}
