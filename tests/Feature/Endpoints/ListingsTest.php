<?php

namespace Endpoints;

use App\Models\Listing;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Tests\TestCase;

class ListingsTest extends TestCase
{
    use RefreshDatabase;

    protected string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = env('APP_URL') . '/api/listings/';
    }

    /** @test */
    public function can_retrieve_all_listings()
    {
        Listing::factory(3)->create();

        $this->getJson($this->endpoint)
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'slug',
                        'company',
                        'location',
                        'logo',
                        'is_highlighted',
                        'is_active',
                        'content',
                        'apply_link',
                        'user_id'
                    ]
                ]
            ]);
    }

    /** @test */
    public function can_retrieve_a_listing()
    {
        $listing = Listing::factory()->create();

        $this->getJson($this->endpoint . $listing->id)
            ->assertOk()
            ->assertExactJson([
                'data' => [
                    'id' => $listing->id,
                    'title' => $listing->title,
                    'slug' => $listing->slug,
                    'company' => $listing->company,
                    'location' => $listing->location,
                    'logo' => $listing->logo,
                    'is_highlighted' => (int)$listing->is_highlighted,
                    'is_active' => (int)$listing->is_active,
                    'content' => $listing->content,
                    'apply_link' => $listing->apply_link,
                    'user_id' => $listing->user_id,
                    'created_at' => $listing->created_at,
                    'updated_at' => $listing->updated_at,
                ]
            ]);
    }

    /** @test
     * @throws ApiErrorException
     * @throws Exception
     */
    public function can_create_a_new_listing()
    {
        $user = User::factory()->create();

        $user->createAsStripeCustomer();

        $this->actingAs($user);

        $listingInformation = [
            'title' => 'New Listing',
            'company' => 'Company Test',
            'location' => 'Remote, United States',
            'apply_link' => fake()->url,
            'content' => fake()->randomHtml,
            'tags' => 'PHP, Laravel, VueJs, Docker, AWS'
        ];

        $stripe = new StripeClient(env('STRIPE_SECRET'));

        $paymentMethod = $stripe->paymentMethods->create([
            'type' => 'card',
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => fake()->month,
                'exp_year' => (int)now()->format('Y') + random_int(1, 4),
                'cvc' => random_int(100, 999)
            ]
        ]);

        $listingInformation['payment_method_id'] = $paymentMethod->id;

        $this->postJson($this->endpoint, $listingInformation)
            ->assertCreated();
    }

    /** @test */
    public function can_delete_a_listing()
    {
        $listing = Listing::factory()->create();

        $this->deleteJson($this->endpoint . $listing->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('listings', $listing->toArray());
    }
}
