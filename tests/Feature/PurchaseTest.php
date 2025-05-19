<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_purchase_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);
        Address::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post("/payment", [
            'item_id' => $item->id,
            'payment_method' => 'クレジットカード',
        ]);

        $response->assertRedirect(); // StripeセッションURLへリダイレクトされる

        // 本番では Stripe セッション生成 → 購入成功後にDBに保存されるので、ここでは一旦保留 or モック対象
        // $this->assertDatabaseHas('purchases', [
        //     'user_id' => $user->id,
        //     'item_id' => $item->id,
        // ]);
    }

    public function test_guest_cannot_purchase_item()
    {
        $item = Item::factory()->create(['is_sold' => false]);

        $response = $this->post('/payment', [
            'item_id' => $item->id,
            'payment_method' => 'クレジットカード',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('purchases', [
            'item_id' => $item->id,
        ]);
    }

    public function test_purchase_requires_payment_method()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);
        Address::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post('/payment', [
            'item_id' => $item->id,
            'payment_method' => '',
        ]);

        $response->assertSessionHasErrors(['payment_method']);
    }

    public function test_purchased_item_shows_sold_label_in_index_page()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'is_sold' => true,
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->actingAs($user)->get(route('items.index'));

        $response->assertStatus(200);
        $response->assertSee('SOLD');
    }
}
