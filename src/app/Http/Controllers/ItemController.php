<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    // 商品一覧画面表示
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

    // 商品詳細画面表示
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

    // 商品出品画面表示
    public function create()
{
    $categories = Category::orderBy('id')->get();
    $conditions = Condition::orderBy('sort_order')->get();

    return view('item.create', compact('categories', 'conditions'));
}

    // 商品出品処理
    public function store(ExhibitionRequest $request)
    {
        $imageUrl = null;
        if ($request->hasFile('item_image')) {
            $path = $request->file('item_image')->store('items', 'public');
            $imageUrl = Storage::url($path);
        }

        $item = Item::create([
            'seller_id'    => $request->user()->id,
            'title'        => $request->title,
            'description'  => $request->description,
            'price'        => $request->price,
            'brand_name'   => $request->brand_name,
            'image_url'    => $imageUrl,
            'condition_id' => $request->condition_id,
        ]);

        if ($request->has('category_ids')) {
            $item->categories()->attach($request->category_ids);
        }

        return redirect()->route('items.index', $item->id)
            ->with('success', '商品を出品しました');
    }
}
