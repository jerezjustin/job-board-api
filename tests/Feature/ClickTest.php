<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ClickTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function clicks_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('clicks'));
    }
}
