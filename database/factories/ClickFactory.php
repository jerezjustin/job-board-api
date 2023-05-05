<?php

namespace Database\Factories;

use App\Models\Listing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Click>
 */
class ClickFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $datetime = $this->faker->dateTimeBetween('-1 month');
        return [
            'user_agent' => $this->faker->userAgent,
            'ip_address' => $this->faker->ipv4,
            'listing_id' => Listing::all()->random()->id ?? Listing::factory()->create(),
            'created_at' => $datetime,
            'updated_at' => $datetime
        ];
    }
}
