<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_updated_address_reflects_on_purchase_page()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);

        // テスト用住所を事前に作成
        $user->address()->create([
            'postal_code' => '000-0000',
            'address_line1' => '旧住所',
            'address_line2' => '旧建物名',
        ]);

        $this->actingAs($user)->post('/purchase/address/' . $item->id, [
            'name' => 'テスト太郎',
            'postal_code' => '123-4567',
            'address_line1' => '東京都新宿区1-2-3',
            'address_line2' => 'テストビル101',
        ]);

        // 購入画面で反映確認
        $response = $this->actingAs($user)->get('/checkout/' . $item->id);
        $response->assertStatus(200);
        $response->assertSee('123-4567');
        $response->assertSee('東京都新宿区1-2-3');
        $response->assertSee('テストビル101');
    }

    public function test_purchased_item_is_linked_with_shipping_address()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);

        // 事前に住所を登録
        $user->address()->create([
            'name' => 'テスト太郎',
            'postal_code' => '987-6543',
            'address_line1' => '大阪府大阪市中央区2-2-2',
            'address_line2' => '大阪ビル202',
        ]);

        // テスト用 completed レコードを直接作成
        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'クレジットカード',
            'status' => 'completed',
            'shipping_postal_code' => '987-6543',
            'shipping_address_line1' => '大阪府大阪市中央区2-2-2',
            'shipping_address_line2' => '大阪ビル202',
        ]);

        // その purchase が DB にあるか確認
        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'shipping_postal_code' => '987-6543',
            'shipping_address_line1' => '大阪府大阪市中央区2-2-2',
            'shipping_address_line2' => '大阪ビル202',
        ]);
    }
}
