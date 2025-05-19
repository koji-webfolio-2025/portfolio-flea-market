<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = FakerFactory::create('ja_JP');

        $users = User::all();
        $items = Item::all();

        $comments = [
            'とても満足しています。',
            'お値段以上の品質でした！',
            '商品が想像以上に良かったです。',
            '丁寧な梱包で安心しました。',
            'またぜひ購入したいと思います。',
            '写真通りで大変満足です。',
            '使い勝手が良く、重宝しています。',
            '発送も早く、助かりました。',
            'このクオリティでこの価格は驚きです。',
            '対応が丁寧で信頼できました。',
            '思ったよりサイズが大きめでした。',
            '色味が写真と少し違いました。',
            '配送には少し時間がかかりました。',
            '期待していたほどではありませんでした。',
            '説明がもう少し詳しいと助かります。',
            '商品の一部に傷があり残念でした。',
            'イメージと違ったため返品しました。',
            '梱包がやや簡易すぎると感じました。',
        ];

        foreach ($items as $item) {
            // 各商品に対して1〜3件のコメントをランダムユーザーで作成
            $commentCount = rand(1, 3);

            for ($i = 0; $i < $commentCount; $i++) {
                Comment::create([
                    'user_id' => $users->random()->id,
                    'item_id' => $item->id,
                    'comment' => $faker->randomElement($comments),
                ]);
            }
        }
    }
}
