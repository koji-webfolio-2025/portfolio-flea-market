<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 全商品が表示される()
    {
        $items = Item::factory()->count(3)->create();

        $response = $this->get('/');

        foreach ($items as $item) {
            $response->assertSee($item->title);
        }
    }

    /** @test */
    public function 購入済み商品は「SOLD」と表示される()
    {
        $soldItem = Item::factory()->create(['is_sold' => true]);

        $response = $this->get('/');

        $response->assertSee('SOLD');
        $response->assertSee($soldItem->title);
    }

    /** @test */
    public function 自分が出品した商品は表示されない()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->get('/');

        $response->assertDontSee($item->title);
    }
}
