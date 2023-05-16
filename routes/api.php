<?php

use App\Http\Controllers\Listing\ApplyListingController;
use App\Http\Controllers\Listing\ListingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('listings', ListingController::class);

Route::post('/listings/{listing}/apply', [ApplyListingController::class, 'store'])->name('listing.apply');

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
