<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateWorkgroupRequest;
use App\Http\Resources\Admin\WorkgroupResource;
use App\Http\Resources\Admin\WorkgroupResourceCollection;
use App\Models\Workgroup;
use Illuminate\Http\Request;

class WorkgroupController extends AdminController
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
            new WorkgroupResourceCollection($request->user()->company->activeWorkgroups)
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
            new WorkgroupResourceCollection($request->user()->company->archivedWorkgroups)
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
        $validated = $request->validate(Workgroup::$rules);
        $validated['company_id'] = $request->user()->company_id;

        $workgroup = Workgroup::create($validated);

        if (!$workgroup) {
            return $this->errorResponse('Workgroup could not be created.', 500);
        }

        $workgroup->attachUsers($request->users);
        $workgroup->attachAreas($request->areas);

        return $this->wrapResponse(new WorkgroupResource(Workgroup::find($workgroup->id)));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Workgroup $workgroup
     * @return \Illuminate\Http\Response
     */
    public function show(Workgroup $workgroup)
    {
        return $this->wrapResponse(new WorkgroupResource($workgroup));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Admin\UpdateWorkgroupRequest $request
     * @param \App\Models\Workgroup $workgroup
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWorkgroupRequest $request, Workgroup $workgroup)
    {
        $validated = $request->validated();
        $validated['company_id'] = $request->user()->company_id;

        $workgroup->fill($validated);

        if (!$workgroup->save()) {
            return $this->errorResponse('Workgroup could not be updated.', 500);
        }

        $workgroup->attachUsers($request->users);
        $workgroup->attachAreas($request->areas);

        return $this->wrapResponse(new WorkgroupResource(Workgroup::find($workgroup->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Workgroup $workgroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Workgroup $workgroup)
    {
        if (!$workgroup->delete()) {
            return $this->errorResponse('Workgroup could not be deleted.', 500);
        }

        return $this->successResponse('Workgroup has been deleted successfully.');
    }

    /**
     * Change status.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Workgroup $workgroup
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, Workgroup $workgroup)
    {
        if (is_null($request->status)) {
            return $this->errorResponse('Status field is missing from the request.', 500);
        }

        if (!is_numeric($request->status) || !in_array($request->status, Workgroup::$statusOptions)) {
            return $this->errorResponse('Status field is invalid.', 500);
        }

        $workgroup->status = (int) $request->status;

        if (!$workgroup->save()) {
            return $this->errorResponse('Status could not be changed.', 500);
        }

        return $this->wrapResponse(new WorkgroupResource($workgroup));
    }
}
