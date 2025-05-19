<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'profile_image' => 'https://placehold.jp/150x150.png',
        ]);

        User::factory()
            ->count(5)
            ->create()
            ->each(function ($user) {
                Address::factory()->create([
                    'user_id' => $user->id,
                ]);
            });
    }
}
