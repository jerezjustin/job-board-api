<?php

namespace App\Http\Controllers\Listing;

use App\Actions\CreateListing;
use App\Actions\CreateTags;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreListingRequest;
use App\Http\Requests\UpdateListingRequest;
use App\Http\Resources\ListingCollection;
use App\Http\Resources\ListingResource;
use App\Models\Listing;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ListingController extends Controller
{
    public function __construct(
        protected CreateListing $createListing,
        protected CreateTags    $createTags,
    ) {
        $this->middleware('auth')->only('store', 'update');
    }

    public function index(): ListingCollection
    {
        $listings = Listing::latest()->paginate($resultsPerPage = 10);

        return new ListingCollection($listings);
    }

    public function store(StoreListingRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $amount = $request->filled('is_highlighted') ? 9999 : 19999;

            Auth::user()->charge($amount, $request->payment_method_id);

            $listing = $this->createListing->handle(
                listingDataObject: \App\DataObjects\Listing::fromArray([
                    ...$request->except('logo'),
                    'logo' => basename($request->file('logo')?->store('public'))
                ])
            );

            $tags = $this->createTags->handle($request->tags);

            $listing->tags()->attach($tags);

            DB::commit();

            return response()->json(new ListingResource($listing), Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(
                ['message' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function show(Listing $listing): ListingResource
    {
        return new ListingResource($listing);
    }

    public function update(UpdateListingRequest $request, Listing $listing): JsonResponse
    {
        try {
            $listing->update([
                ...$request->validated(),
                'logo' => basename($request->file('logo')?->store('public'))
                    ?? $listing->logo
            ]);

            if ($request->tags) {
                $listing->tags()->attach(
                    $this->createTags->handle($request->tags)
                );
            }

            return response()->json(new ListingResource($listing), Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(
                ['message' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function destroy(Listing $listing): \Illuminate\Http\Response
    {
        $listing->delete();

        return response()->noContent();
    }
}