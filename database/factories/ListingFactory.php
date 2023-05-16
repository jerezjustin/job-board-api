<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $datetime = $this->faker->dateTimeBetween('-1 month');

        $content = '';
        for ($i = 0; $i < 5; $i++) {
            $content .= '<p class="mb-4">' . $this->faker->paragraph . '</p>';
        }

        return [
            'title' => $this->faker->jobTitle,
            'company' => $company = $this->faker->company,
            'location' => $this->faker->country,
            'logo' => $company . '_logo_' . 'png',
            'is_highlighted' => $this->faker->boolean(chanceOfGettingTrue: 25),
            'is_active' => true,
            'content' => $content,
            'apply_link' => $this->faker->url,
            'created_at' => $datetime,
            'updated_at' => $datetime,
            'user_id' => User::factory()->create(),
        ];
    }
}
