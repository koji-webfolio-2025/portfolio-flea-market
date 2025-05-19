<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_edit_form_displays_current_values()
    {
        $user = User::factory()->create([
            'name' => '初期ユーザー名',
            'profile_image' => 'https://placehold.jp/100x100.png',
        ]);

        // 住所データを事前作成
        Address::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address_line1' => '東京都港区1-1-1',
            'address_line2' => '初期マンション101',
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee('初期ユーザー名');
        $response->assertSee('https://placehold.jp/100x100.png');
        $response->assertSee('123-4567');
        $response->assertSee('東京都港区1-1-1');
        $response->assertSee('初期マンション101');
    }

    public function test_user_profile_can_be_updated_successfully()
    {
        $user = User::factory()->create();
        Address::create([
            'user_id' => $user->id,
            'postal_code' => '111-1111',
            'address_line1' => '古い住所1',
            'address_line2' => '古い住所2',
        ]);

        $response = $this->actingAs($user)->post('/mypage/profile', [
            'name' => '更新後ユーザー名',
            'postal_code' => '987-6543',
            'address_line1' => '大阪府中央区2-2-2',
            'address_line2' => '大阪ビル202',
        ]);

        $response->assertRedirect('/mypage');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => '更新後ユーザー名',
        ]);
        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'postal_code' => '987-6543',
            'address_line1' => '大阪府中央区2-2-2',
            'address_line2' => '大阪ビル202',
        ]);
    }
}
