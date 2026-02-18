<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function create(Item $item)
    {
        $user = request()->user();

        return view('purchase.create', compact('item', 'user'));
    }

    public function editAddress(Item $item)
    {
        $user = request()->user();

        return view('purchase.address', compact('item', 'user'));
    }

    public function createCheckout(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'payment_method' => 'required|in:card,konbini',
        ]);

        $item = Item::findOrFail($validated['item_id']);

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $checkout_session = \Stripe\Checkout\Session::create([
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
            'payment_method_types' => [$validated['payment_method']],
            'success_url' => url('/'),
            'cancel_url' => url('/'),
        ]);

        return redirect($checkout_session->url);
    }
}
