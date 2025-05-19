<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Address;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'sell');

        $purchasedItems = Purchase::where('user_id', $user->id)
            ->with('item')
            ->get()
            ->pluck('item');

        $soldItems = Item::where('user_id', $user->id)->get();

        return view('user.profile', compact('user', 'purchasedItems', 'soldItems', 'tab'));
    }

    public function edit()
    {
        $user = Auth::user();
        $address = Address::where('user_id', $user->id)->first();
        return view('user.edit', compact('user', 'address'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        $user->name = $request->name;
        $user->save();

        Address::updateOrCreate(
            ['user_id' => $user->id],
            [
                'postal_code' => $request->postal_code,
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
            ]
        );

        return redirect('/mypage')->with('success', 'プロフィールを更新しました！');
    }
}
