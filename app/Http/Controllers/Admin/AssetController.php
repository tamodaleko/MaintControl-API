<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateAssetRequest;
use App\Http\Resources\Admin\AssetResource;
use App\Http\Resources\Admin\AssetResourceCollection;
use App\Models\Asset\Asset;
use Illuminate\Http\Request;

class AssetController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->wrapResponse(
            new AssetResourceCollection($request->user()->company->activeAssets)
        );
    }

    /**
     * Get archived items.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function archived(Request $request)
    {
        return $this->wrapResponse(
            new AssetResourceCollection($request->user()->company->archivedAssets)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(Asset::$rules);
        $validated['company_id'] = $request->user()->company_id;

        $asset = Asset::create($validated);

        if (!$asset) {
            return $this->errorResponse('Asset could not be created.', 500);
        }

        $asset->attachSpares($request->spares);
        $asset->attachSubAssets($request->assets);

        return $this->wrapResponse(new AssetResource(Asset::find($asset->id)));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Asset\Asset $asset
     * @return \Illuminate\Http\Response
     */
    public function show(Asset $asset)
    {
        return $this->wrapResponse(new AssetResource($asset));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Admin\UpdateAssetRequest $request
     * @param \App\Models\Asset\Asset $asset
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        $validated = $request->validated();
        $validated['company_id'] = $request->user()->company_id;

        $asset->fill($validated);

        if (!$asset->save()) {
            return $this->errorResponse('Asset could not be updated.', 500);
        }

        $asset->attachSpares($request->spares);
        $asset->attachSubAssets($request->assets);

        return $this->wrapResponse(new AssetResource(Asset::find($asset->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Asset\Asset $asset
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Asset $asset)
    {
        if (!$asset->delete()) {
            return $this->errorResponse('Asset could not be deleted.', 500);
        }

        return $this->successResponse('Asset has been deleted successfully.');
    }

    /**
     * Change status.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Asset\Asset $asset
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, Asset $asset)
    {
        if (is_null($request->status)) {
            return $this->errorResponse('Status field is missing from the request.', 500);
        }

        if (!is_numeric($request->status) || !in_array($request->status, Asset::$statusOptions)) {
            return $this->errorResponse('Status field is invalid.', 500);
        }

        $asset->status = (int) $request->status;

        if (!$asset->save()) {
            return $this->errorResponse('Status could not be changed.', 500);
        }

        return $this->wrapResponse(new AssetResource($asset));
    }
}
