<?php

namespace App\Actions;

use Illuminate\Support\Facades\Auth;

class CreateListing
{
    public function handle(\App\DataObjects\Listing $listingDataObject): \App\Models\Listing
    {
        return Auth::user()->listings()->create([
            'title' => $listingDataObject->title,
            'company' => $listingDataObject->company,
            'location' => $listingDataObject->location,
            'logo' => $listingDataObject->logo,
            'is_highlighted' => $listingDataObject->highlighted,
            'is_active' => $listingDataObject->active,
            'content' => $listingDataObject->content,
            'apply_link' => $listingDataObject->applyLink
        ]);
    }
}
