<?php

namespace App\Http\Controllers;
use Illuminate\Http\RedirectResponse;
use App\Models\Item;
use App\Http\Requests\CommentRequest;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Item $item): RedirectResponse
{
    $user = $request->user();
    $validated = $request->validated();

    $item->comments()->create([
        'user_id'      => $user->id,
        'comment_text' => $validated['comment_text'],
    ]);

    return back();
}
}
