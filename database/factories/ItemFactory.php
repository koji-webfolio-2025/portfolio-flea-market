<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->words(2, true),
            'description' => $this->faker->text(100),
            'price' => $this->faker->numberBetween(1000, 10000),
            'brand_name' => $this->faker->company(),
            'condition' => $this->faker->randomElement(['新品・未使用', '目立った傷や汚れなし', 'やや傷や汚れあり']),
            'image_url' => 'https://placehold.jp/300x300.png',
            'is_sold' => false,
        ];
    }

    public function sold()
    {
        return $this->state([
            'is_sold' => true,
        ]);
    }
}
