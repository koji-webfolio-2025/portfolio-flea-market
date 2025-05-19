<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログインユーザーが商品にいいねできる
     */
    public function test_authenticated_user_can_like_an_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post(route('item.like', $item->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /**
     * ログインユーザーが商品へのいいねを解除できる
     */
    public function test_authenticated_user_can_unlike_an_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 事前にいいねしておく
        $user->likes()->attach($item->id);

        $response = $this->actingAs($user)->post(route('item.unlike', $item->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /**
     * 未ログインユーザーはいいねできない
     */
    public function test_guest_cannot_like_an_item()
    {
        $item = Item::factory()->create();

        $response = $this->post(route('item.like', $item->id));

        $response->assertRedirect('/login');
    }
}
