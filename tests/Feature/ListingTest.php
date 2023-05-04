<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function listings_table_exists()
    {
        $this->assertTrue(Schema::hasTable('listings'));
    }
}
