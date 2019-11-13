<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Workgroup\WorkgroupResourceCollection;
use Illuminate\Http\Request;

class WorkgroupController extends Controller
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
            new WorkgroupResourceCollection(
                $request->user()->getAvailableWorkgroups($area_id)
            )
        );
    }
}
