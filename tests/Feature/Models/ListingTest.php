<?php

namespace Models;

use App\Models\Click;
use App\Models\Listing;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ListingTest extends TestCase
{
    use RefreshDatabase;

    public const COUNT = 2;

    /** @test */
    public function listings_table_exists()
    {
        $this->assertTrue(Schema::hasTable('listings'));
    }

    /** @test */
    public function listing_has_tags_and_clicks()
    {
        Listing::factory()->create()->each(function ($listing) {
            $listing->tags()->attach(Tag::factory(self::COUNT)->create());

            Click::factory(self::COUNT)->create(['listing_id' => $listing]);
        });

        $listing = Listing::withCount('tags', 'clicks')->latest()->first();

        $this->assertSame(self::COUNT, $listing->tags_count);

        $this->assertSame(self::COUNT, $listing->clicks_count);
    }
}
