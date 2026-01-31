<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');

        if ($tab === 'mylist' && !$request->user()) {
            return view('item.index', [
                'items' => collect(),
                'tab'   => $tab,
            ]);
        }

        $items = Item::query()
            ->with('purchase')
            ->when($tab === 'mylist', fn ($q) =>
                $q->whereHas('likes', fn ($likeQ) =>
                    $likeQ->where('user_id', $request->user()->id)
                )
            )
            ->get();

        return view('item.index', compact('items', 'tab'));
    }
}
