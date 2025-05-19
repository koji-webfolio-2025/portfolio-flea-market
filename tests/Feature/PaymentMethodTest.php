<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_redirects_to_stripe_checkout()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $item = Item::factory()->create();

        // 支払い処理にPOST（実際にStripeへリダイレクトされるはず）
        $response = $this->actingAs($user)->post('/payment', [
            'item_id' => $item->id,
            'payment_method' => 'クレジットカード',
        ]);

        // リダイレクト確認
        $response->assertRedirect();
        $this->assertStringContainsString('https://checkout.stripe.com', $response->headers->get('Location'));
    }
}
