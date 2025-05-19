<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_creation_saves_data_correctly()
    {
        // カテゴリ事前投入
        $categories = Category::factory()->count(2)->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', [
            'title' => 'テスト商品',
            'description' => 'これはテスト用の商品説明です。',
            'categories' => $categories->pluck('id')->toArray(),
            'condition' => '新品・未使用',
            'price' => 5000,
            'image_url' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $response->assertRedirect('/');

        // items テーブルに保存されているか確認
        $this->assertDatabaseHas('items', [
            'title' => 'テスト商品',
            'description' => 'これはテスト用の商品説明です。',
            'condition' => '新品・未使用',
            'price' => 5000,
        ]);

        // 中間テーブルにもしっかり保存されているか確認
        $item = \App\Models\Item::where('title', 'テスト商品')->first();
        foreach ($categories as $category) {
            $this->assertDatabaseHas('category_item', [
                'item_id' => $item->id,
                'category_id' => $category->id,
            ]);
        }
    }

    public function test_item_creation_requires_authentication()
    {
        $categories = Category::factory()->count(1)->create();

        $response = $this->post('/sell', [
            'title' => 'ログインなしの商品',
            'description' => 'これはテスト用の商品説明です。',
            'categories' => $categories->pluck('id')->toArray(),
            'condition' => '新品・未使用',
            'price' => 5000,
            'image_url' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('items', [
            'title' => 'ログインなしの商品',
        ]);
    }
}
