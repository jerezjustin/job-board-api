<?php

namespace App\Http\Controllers;

use App\Http\Resources\ListingCollection;
use App\Http\Resources\ListingResource;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public const RESULTS_PER_PAGE = 10;

    public function index(): ListingCollection
    {
        $listings = Listing::latest()->paginate(self::RESULTS_PER_PAGE);

        return new ListingCollection($listings);
    }

    public function store(Request $request): ListingResource
    {
        //
    }

    public function show(Listing $listing): ListingResource
    {
        return new ListingResource($listing);
    }

    public function update(Request $request, Listing $listing)
    {
        //
    }

    public function destroy(Listing $listing): \Illuminate\Http\Response
    {
        $listing->delete();

        return response()->noContent();
    }
}
