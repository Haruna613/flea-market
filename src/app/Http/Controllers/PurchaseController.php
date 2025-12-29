<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

class PurchaseController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function showPurchase($item_id)
    {
        $item = Item::findOrFail($item_id);

        if ($item->isSold()) {
            return redirect()->route('item.detail', $item_id)->with('error', 'この商品は既に売却済みです。');
        }

        if ($item->user_id === Auth::id()) {
            return redirect()->route('item.detail', $item_id)->with('error', 'ご自身の出品物は購入できません。');
        }

        $user = Auth::user();
        $shippingInfo = [
            'postal_code'   => $user->postal_code ?? '',
            'address'       => $user->address ?? '',
            'building_name' => $user->building_name ?? '',
        ];

        return view('purchase', compact('item', 'shippingInfo'));
    }

    public function showPurchaseAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        $shippingInfo = [
            'postal_code'   => $user->postal_code ?? '',
            'address'       => $user->address ?? '',
            'building_name' => $user->building_name ?? '',
        ];

        return view('purchase-address', compact('item', 'shippingInfo'));
    }

    public function updatePurchaseAddress(AddressRequest $request, $item_id)
    {
        $user = Auth::user();
        $user->postal_code = $request->input('postal_code');
        $user->address = $request->input('address');
        $user->building_name = $request->input('building_name');
        $user->save();

        return redirect()->route('item.purchase.show', ['item_id' => $item_id]);
    }

    public function createCheckoutSession(PurchaseRequest $request, Item $item)
    {
        if ($item->isSold()) {
            return response()->json(['error' => '申し訳ありません、この商品は既に売り切れです。'], 400);
        }

        $paymentMethod = $request->input('payment_method');

        $order = Order::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'price' => $item->price,
            'status' => 'pending',
            'shipping_postal_code' => $request->input('postal_code'),
            'shipping_address_line1' => $request->input('address'),
            'shipping_building_name' => $request->input('building_name'),
        ]);

        try {
            $session = CheckoutSession::create([
                'payment_method_types' => $paymentMethod === 'card' ? ['card'] : ['konbini'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => ['name' => $item->name],
                        'unit_amount' => $item->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('top'),
                'cancel_url' => route('item.detail', $item->id),
                'customer_email' => Auth::user()->email,
                'metadata' => [
                    'order_id' => $order->id,
                ],
                'payment_method_options' => [
                    'konbini' => [
                        'expires_after_days' => 3,
                    ],
                ],
            ]);

            return response()->json(['id' => $session->id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}