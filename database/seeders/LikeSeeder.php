<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $items = Item::all();

        foreach ($users as $user) {
            // 各ユーザーがランダムに1〜5件いいねする
            $likedItems = $items->random(rand(1, 5));

            foreach ($likedItems as $item) {
                // 重複登録を避けて attach 的に登録
                Like::firstOrCreate([
                    'user_id' => $user->id,
                    'item_id' => $item->id,
                ]);
            }
        }
    }
}
