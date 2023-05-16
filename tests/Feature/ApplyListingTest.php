<?php

namespace Tests\Feature;

use App\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplyListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_record_apply_link_clicks()
    {
        $listing = Listing::factory()->create();

        $this->postJson(route('listing.apply', ['listing' => $listing]))
            ->assertRedirect();
    }
}
