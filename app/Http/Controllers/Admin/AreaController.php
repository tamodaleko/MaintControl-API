<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateAreaRequest;
use App\Http\Resources\Admin\AreaResource;
use App\Http\Resources\Admin\AreaResourceCollection;
use App\Models\Area\Area;
use Illuminate\Http\Request;

class AreaController extends AdminController
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
            new AreaResourceCollection($request->user()->company->activeAreas)
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
            new AreaResourceCollection($request->user()->company->archivedAreas)
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
        $validated = $request->validate(Area::$rules);
        $validated['company_id'] = $request->user()->company_id;

        $area = Area::create($validated);

        if (!$area) {
            return $this->errorResponse('Area could not be created.', 500);
        }

        $area->status = Area::STATUS_ACTIVE;

        $area->attachUsers($request->users);
        $area->attachWorkgroups($request->workgroups);
        $area->attachAssets($request->assets);

        return $this->wrapResponse(new AreaResource($area));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Area\Area $area
     * @return \Illuminate\Http\Response
     */
    public function show(Area $area)
    {
        return $this->wrapResponse(new AreaResource($area));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Admin\UpdateAreaRequest $request
     * @param \App\Models\Area\Area $area
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAreaRequest $request, Area $area)
    {
        $validated = $request->validated();
        $validated['company_id'] = $request->user()->company_id;

        $area->fill($validated);

        if (!$area->save()) {
            return $this->errorResponse('Area could not be updated.', 500);
        }

        $area->attachUsers($request->users);
        $area->attachWorkgroups($request->workgroups);
        $area->attachAssets($request->assets);

        return $this->wrapResponse(new AreaResource($area));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Area\Area $area
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Area $area)
    {
        if (!$area->delete()) {
            return $this->errorResponse('Area could not be deleted.', 500);
        }

        return $this->successResponse('Area has been deleted successfully.');
    }

    /**
     * Change status.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Area\Area $area
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, Area $area)
    {
        if (is_null($request->status)) {
            return $this->errorResponse('Status field is missing from the request.', 500);
        }

        if (!is_numeric($request->status) || !in_array($request->status, Area::$statusOptions)) {
            return $this->errorResponse('Status field is invalid.', 500);
        }

        $area->status = (int) $request->status;

        if (!$area->save()) {
            return $this->errorResponse('Status could not be changed.', 500);
        }

        return $this->wrapResponse(new AreaResource($area));
    }
}
