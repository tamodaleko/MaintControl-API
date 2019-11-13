<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateSpareRequest;
use App\Http\Resources\Admin\SpareResource;
use App\Http\Resources\Admin\SpareResourceCollection;
use App\Models\Spare;
use Illuminate\Http\Request;

class SpareController extends AdminController
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
            new SpareResourceCollection($request->user()->company->activeSpares)
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
            new SpareResourceCollection($request->user()->company->archivedSpares)
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
        $validated = $request->validate(Spare::$rules);
        $validated['company_id'] = $request->user()->company_id;

        $spare = Spare::create($validated);

        if (!$spare) {
            return $this->errorResponse('Spare could not be created.', 500);
        }

        $spare->attachAssets($request->assets);

        return $this->wrapResponse(new SpareResource(Spare::find($spare->id)));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Spare $spare
     * @return \Illuminate\Http\Response
     */
    public function show(Spare $spare)
    {
        return $this->wrapResponse(new SpareResource($spare));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Admin\UpdateSpareRequest $request
     * @param \App\Models\Spare $spare
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSpareRequest $request, Spare $spare)
    {
        $validated = $request->validated();
        $validated['company_id'] = $request->user()->company_id;

        $spare->fill($validated);

        if (!$spare->save()) {
            return $this->errorResponse('Spare could not be updated.', 500);
        }

        $spare->attachAssets($request->assets);

        return $this->wrapResponse(new SpareResource(Spare::find($spare->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Spare $spare
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Spare $spare)
    {
        if (!$spare->delete()) {
            return $this->errorResponse('Spare could not be deleted.', 500);
        }

        return $this->successResponse('Spare has been deleted successfully.');
    }

    /**
     * Change status.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Spare $spare
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, Spare $spare)
    {
        if (is_null($request->status)) {
            return $this->errorResponse('Status field is missing from the request.', 500);
        }

        if (!is_numeric($request->status) || !in_array($request->status, Spare::$statusOptions)) {
            return $this->errorResponse('Status field is invalid.', 500);
        }

        $spare->status = (int) $request->status;

        if (!$spare->save()) {
            return $this->errorResponse('Status could not be changed.', 500);
        }

        return $this->wrapResponse(new SpareResource($spare));
    }
}
