<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MypageController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $tab  = $request->input('tab', 'selling');

        $items = Item::with('purchase')
            ->when($tab === 'purchased', fn($q) => $q->purchasedBy($user))
            ->when($tab === 'selling', fn($q) => $q->sellingBy($user))
            ->latest()
            ->get();

        return view('mypage.index', compact('items', 'tab', 'user'));
    }
}
