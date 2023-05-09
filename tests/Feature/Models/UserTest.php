<?php

namespace Models;

use App\Models\Listing;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public const LISTINGS_COUNT = 3;

    /** @test */
    public function users_table_exists()
    {
        $this->assertTrue(Schema::hasTable('users'));
    }

    /** @test */
    public function user_has_listings()
    {
        User::factory()->create()->each(function ($user) {
            $listings = Listing::factory(self::LISTINGS_COUNT)->create([
                'user_id' => $user->id
            ]);
        });

        $user = User::withCount('listings')->latest()->first();

        $this->assertSame(self::LISTINGS_COUNT, $user->listings_count);
    }

    /** @test */
    public function user_has_roles()
    {
        $user = User::factory()->createOne();

        $role = Role::create(['name' => 'test role']);

        $user->assignRole($role);

        $this->assertTrue($user->hasRole($role->name));
    }
}
