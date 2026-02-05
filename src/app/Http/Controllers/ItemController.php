<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        $tab     = $request->input('tab', 'recommend');
        $keyword = $request->input('keyword');
        $user    = $request->user();


        if ($tab === 'mylist' && !$user) {
            return view('item.index', [
                'items'   => collect(),
                'tab'     => $tab,
                'keyword' => $keyword,
            ]);
        }


        $items = Item::with('purchase')
            ->search($keyword)
            ->when($tab === 'recommend', fn($q) => $q->recommendFor($user))
            ->when($tab === 'mylist', fn($q) => $q->mylistFor($user))
            ->get();

        return view('item.index', compact('items', 'tab', 'keyword'));
    }

    
    public function show(Item $item)
    {
        $item->load([
            'condition',
            'categories',
            'comments.user',
        ])->loadCount([
            'comments',
            'likes',
        ]);

        return view('item.show', compact('item'));
    }
}
