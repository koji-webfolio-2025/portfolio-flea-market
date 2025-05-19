<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_profile_page_displays_correct_information()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'https://placehold.jp/100x100.png',
        ]);

        // 出品した商品
        $itemsSold = Item::factory()->count(2)->create(['user_id' => $user->id]);

        // 購入した商品
        $itemsPurchased = Item::factory()->count(2)->create(['is_sold' => true]);
        foreach ($itemsPurchased as $item) {
            Purchase::factory()->create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
        }

        // 購入履歴（buyタブ）の確認
        $responseBuy = $this->actingAs($user)->get('/mypage?tab=buy');
        $responseBuy->assertStatus(200);
        $responseBuy->assertSee('テストユーザー');
        $responseBuy->assertSee('https://placehold.jp/100x100.png');

        foreach ($itemsPurchased as $item) {
            $responseBuy->assertSee($item->title);
        }

        // 出品履歴（sellタブ）の確認
        $responseSell = $this->actingAs($user)->get('/mypage?tab=sell');

        foreach ($itemsSold as $item) {
            $responseSell->assertSee($item->title);
        }
    }

}
