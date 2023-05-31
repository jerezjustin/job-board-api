<?php

namespace Endpoints;

use App\Http\Resources\ListingResource;
use App\Models\Listing;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Cashier\Exceptions\CustomerAlreadyCreated;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Tests\TestCase;

class ListingsTest extends TestCase
{
    use RefreshDatabase;

    protected string $endpoint;

    protected User $user;

    /**
     * @throws CustomerAlreadyCreated
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = env('APP_URL') . '/api/listings/';

        $this->user = User::factory()->create();

        $this->user->createAsStripeCustomer();
    }

    /**
     * @test
     */
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
                        'salary',
                        'user_id'
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
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
                    'salary' => $listing->salary,
                    'user_id' => $listing->user_id,
                    'created_at' => $listing->created_at,
                    'updated_at' => $listing->updated_at,
                ]
            ]);
    }

    /**
     * @test
     * @throws ApiErrorException
     * @throws Exception
     */
    public function can_create_a_new_listing()
    {
        $this->actingAs($this->user);

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

    /**
     * @test
     */
    public function can_update_an_existing_listing()
    {
        $this->actingAs($this->user);

        $listing = $this->user->listings()->create([
            'title' => fake()->jobTitle,
            'company' => fake()->company,
            'location' => fake()->country,
            'content' => fake()->randomHtml,
            'apply_link' => fake()->url,
            'is_active' => true,
            'is_highlighted' => fake()->boolean,
        ]);

        $response = $this->patchJson(route('listings.update', $listing->id), [
            'location' => fake()->country,
            'content' => fake()->randomHtml
        ]);

        $response->assertOk();

        $this->assertEquals($response->json(), [
            ...(new ListingResource($listing->refresh()))->resolve(),
            'created_at' => $listing->created_at->jsonSerialize(),
            'updated_at' => $listing->updated_at->jsonSerialize()
        ]);
    }

    /**
     * @test
     */
    public function can_delete_a_listing()
    {
        $this->actingAs($this->user);

        $listing = Listing::factory()->create(['user_id' => $this->user]);

        $this->deleteJson($this->endpoint . $listing->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('listings', $listing->toArray());
    }
}
