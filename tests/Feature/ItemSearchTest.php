<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 部分一致検索で商品が取得できることを確認
     */
    public function test_search_items_by_keyword()
    {
        $user = User::factory()->create();
        Item::factory()->create([
            'title' => 'Apple Watch',
            'user_id' => $user->id,
        ]);
        Item::factory()->create([
            'title' => 'iPad Pro',
            'user_id' => $user->id,
        ]);
        Item::factory()->create([
            'title' => 'Galaxy',
            'user_id' => $user->id,
        ]);

        $response = $this->get('/?keyword=Apple');

        $response->assertSee('Apple Watch');
        $response->assertDontSee('iPad Pro');
        $response->assertDontSee('Galaxy');
    }

    /**
     * マイリスト画面でも検索状態が維持されていることを確認
     */
    public function test_mylist_search_keyword_remains()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/mylist?keyword=Apple');

        $response->assertSee('Apple'); // フォームや結果内でキーワードが維持されているかを確認
    }
}
