<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateLocationRequest;
use App\Http\Resources\Admin\Location\LocationResource;
use App\Models\Location\Location;
use Illuminate\Http\Request;

class LocationController extends AdminController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(Location::$rules);
        $validated['company_id'] = $request->user()->company_id;

        $location = Location::create($validated);

        if (!$location) {
            return $this->errorResponse('Location could not be created.', 500);
        }

        $location->addShifts($request->shifts);
        $location->load('shifts');

        return $this->wrapResponse(new LocationResource($location));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Admin\UpdateLocationRequest $request
     * @param \App\Models\Location\Location $location
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLocationRequest $request, Location $location)
    {
        $validated = $request->validated();
        $validated['company_id'] = $request->user()->company_id;

        $location->fill($validated);

        if (!$location->save()) {
            return $this->errorResponse('Location could not be updated.', 500);
        }

        $location->addShifts($request->shifts);
        $location->load('shifts');

        return $this->wrapResponse(new LocationResource($location));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Location\Location $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Location $location)
    {
        if (!$location->delete()) {
            return $this->errorResponse('Location could not be deleted.', 500);
        }

        return $this->successResponse('Location has been deleted successfully.');
    }
}
