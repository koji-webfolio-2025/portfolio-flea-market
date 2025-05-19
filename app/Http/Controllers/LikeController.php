<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store(Item $item)
    {
        Auth::user()->likes()->attach($item->id);
        return back()->with('success', '商品をお気に入りに追加しました。');
    }

    public function destroy(Item $item)
    {
        Auth::user()->likes()->detach($item->id);
        return back()->with('success', 'お気に入りから削除しました。');
    }
}
