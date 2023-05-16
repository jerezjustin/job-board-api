<?php

namespace App\Http\Controllers\Listing;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApplyListingController extends Controller
{
    public function store(Request $request, Listing $listing): RedirectResponse
    {
        if ($listing->clicks()->where('ip_address', $request->ip())->doesntExist()) {
            $listing->clicks()->create([
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip()
            ]);
        }

        return redirect($listing->apply_link);
    }
}
