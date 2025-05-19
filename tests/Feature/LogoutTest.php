<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_user_can_logout_successfully()
    {
        // ユーザーを作成しログイン状態にする
        $user = User::factory()->create();
        $this->actingAs($user);

        // ログアウトリクエストを送信
        $response = $this->post('/logout');

        // ログイン状態が解除されているか確認
        $this->assertGuest();

        // ログアウト後にトップページにリダイレクトされるか確認
        $response->assertRedirect('/');
    }
}
