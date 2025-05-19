<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function item_detail_page_displays_all_required_information()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $categories = Category::factory()->count(2)->create();
        $item->categories()->attach($categories);

        $comment = Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'comment' => 'テストコメント',
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200)
            ->assertSee($item->title)
            ->assertSee(number_format($item->price))
            ->assertSee($item->description)
            ->assertSee($categories[0]->name)
            ->assertSee($categories[1]->name)
            ->assertSee($item->condition)
            ->assertSee($user->name)
            ->assertSee($comment->comment);
    }

    /** @test */
    public function item_detail_displays_multiple_categories()
    {
        $item = Item::factory()->create();
        $categories = Category::factory()->count(3)->create();
        $item->categories()->attach($categories);

        $response = $this->get('/item/' . $item->id);

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }
}
