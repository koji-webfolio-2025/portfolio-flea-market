<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_liked_items_are_displayed()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create();
        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get('/mylist');
        $response->assertSee($item->title);
    }

    /** @test */
    public function test_sold_items_in_mylist_have_sold_label()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $soldItem = Item::factory()->create(['is_sold' => true]);
        Like::create([
            'user_id' => $user->id,
            'item_id' => $soldItem->id,
        ]);

        $response = $this->get('/mylist');
        $response->assertSee('SOLD');
    }

    /** @test */
    public function test_mylist_does_not_show_own_items()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $ownItem = Item::factory()->create(['user_id' => $user->id]);
        Like::create([
            'user_id' => $user->id,
            'item_id' => $ownItem->id,
        ]);

        $response = $this->get('/mylist');
        $response->assertDontSee($ownItem->title);
    }

    /** @test */
    public function test_mylist_redirects_for_guests()
    {
        $response = $this->get('/mylist');
        $response->assertRedirect('/login');
    }
}
