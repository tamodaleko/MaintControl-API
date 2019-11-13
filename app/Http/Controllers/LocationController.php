<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Location\LocationResource;
use App\Http\Resources\Location\LocationResourceCollection;
use App\Models\Location\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locations = $request->user()->getAvailableLocations();
        $locations->load('shifts');

        return $this->wrapResponse(
            new LocationResourceCollection(
                $locations
            )
        );
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Location $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        $location->load('shifts');

        return $this->wrapResponse(new LocationResource($location));
    }
}
