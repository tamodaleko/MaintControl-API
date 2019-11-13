<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\UpdateMeterReadingsRequest;
use App\Http\Resources\Asset\AssetResource;
use App\Http\Resources\Asset\AssetResourceCollection;
use App\Models\Asset\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
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
        $area_id = ($request->has('area_id') && is_numeric($request->area_id)) ? $request->area_id : null;

        return $this->wrapResponse(
            new AssetResourceCollection(
                $request->user()->getAvailableAssets($area_id)
            )
        );
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Asset\Asset $asset
     * @return \Illuminate\Http\Response
     */
    public function show(Asset $asset)
    {
        $asset->load('meterReadings');

        return $this->wrapResponse(new AssetResource($asset));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Asset\UpdateMeterReadingsRequest $request
     * @param \App\Models\Asset\Asset $asset
     * @return \Illuminate\Http\Response
     */
    public function updateMeterReadings(UpdateMeterReadingsRequest $request, Asset $asset)
    {
        $validated = $request->validated();
        $validated['company_id'] = $request->user()->company_id;

        $asset->fill($validated);

        if (!$asset->save()) {
            return $this->errorResponse('Asset could not be updated.', 500);
        }

        $asset->logMeterReading($request->user()->id);

        return $this->wrapResponse(new AssetResource($asset));
    }
}
