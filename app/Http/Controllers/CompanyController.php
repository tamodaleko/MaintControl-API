<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Company\CompanyPreDefinedResource;
use Illuminate\Http\Request;

class CompanyController extends Controller
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
     * Show company predefined data.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function predefinedData(Request $request)
    {
        $request->user()->company->load(
            'priorities', 
            'skills', 
            'permits', 
            'criticals', 
            'activityTypesCO', 
            'activityTypesPO', 
            'additionalFieldsCO', 
            'additionalFieldsPO', 
            'assetCategories', 
            'conditions', 
            'units',
            'currencies'
        );

        return $this->wrapResponse(
            new CompanyPreDefinedResource($request->user()->company)
        );
    }
}
