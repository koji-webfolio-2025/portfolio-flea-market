<?php

namespace Database\Factories;

use App\Models\Address;
use Database\Factories\Providers\PrefectureProvider;
use Faker\Generator as Faker;
use Faker\Provider\ja_JP\Address as JapaneseAddressProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition()
    {
        $faker = $this->faker;
        $faker->addProvider(new JapaneseAddressProvider($faker));
        $faker->addProvider(new PrefectureProvider($faker));

        return [
            'user_id' => \App\Models\User::factory(),
            'postal_code' => $faker->postcode(),
            'address_line1' => $faker->prefecture() . $faker->city(),
            'address_line2' => $faker->streetAddress(),
        ];
    }
}
