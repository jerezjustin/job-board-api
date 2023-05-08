<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Click;
use App\Models\Listing;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        collect(\App\Enums\Role::cases())->each(function ($role) {
            \App\Models\Role::create([
                'name' => $role->value,
            ]);
        });

        $tags = Tag::factory(10)->create();

        User::factory(10)->create()->each(function ($user) use ($tags) {
            Listing::factory(rand(1, 4))->create([
                'user_id' => $user->id
            ])->each(function ($listing) use ($tags) {
                $listing->tags()->attach($tags->random(rand(1, 4)));
            });
        });

        // Create fake clicks for listings
        Click::factory(100)->create();
    }
}
