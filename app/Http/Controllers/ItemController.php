<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();

        // 自分が出品した商品を除外
        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('title', 'like', "%{$keyword}%");
        }

        // 並び順を追加（おすすめ）
        $items = $query->orderBy('created_at', 'desc')->paginate(9);

        // ここで中身を確認（ページ1なら1〜9件）
        //dd($items->pluck('id'));

        return view('items.index', compact('items'));
    }

    public function mylist(Request $request)
    {
        $user = Auth::user();
        $likedItemIds = $user->likes()->pluck('item_id'); // ← いいねされた商品のIDを取得

        $query = Item::whereIn('id', $likedItemIds)
            ->where('user_id', '!=', $user->id); // 自分の商品除外

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('title', 'like', "%{$keyword}%");
        }

        $items = $query->paginate(9);
        return view('items.mylist', ['likedItems' => $items]);
    }

    public function show($item_id)
    {
        $item = Item::with(['comments.user', 'categories'])->findOrFail($item_id);
        return view('items.show', compact('item'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $path = $request->file('image_url')->store('items', 'public');
        $imageUrl = '/storage/' . $path;

        $item = Item::create([
            'user_id' => Auth::id(),
            'image_url' => $imageUrl,
            'title' => $request->title,
            'brand_name' => $request->brand_name,
            'description' => $request->description,
            'condition' => $request->condition,
            'price' => $request->price,
            'is_sold' => false,
        ]);

        $item->categories()->sync($request->input('categories'));
        return redirect('/')->with('success', '商品を出品しました！');
    }

    public function soldItem()
    {
        $items = Item::where('user_id', Auth::id())->get();
        return view('user.sold_items', compact('items'));
    }

    public function addComment(CommentRequest $request, $item_id)
    {
        Comment::create([
            'item_id' => $item_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'コメントを投稿しました！');
    }
}
