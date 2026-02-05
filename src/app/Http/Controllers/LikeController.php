<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Item;

use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like(Item $item)
{
    $user = auth()->user();
    $isLiked = $user->likes()->where('item_id', $item->id)->exists();

    if ($isLiked) {
        $user->likes()->detach($item);
    } else {
        $user->likes()->attach($item);
    }

    return back();
}
}
