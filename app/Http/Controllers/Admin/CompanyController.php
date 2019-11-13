<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateCompanyRequest;
use App\Http\Requests\Admin\UpdateCompanyPredefinedFieldsRequest;
use App\Http\Resources\Admin\Company\CompanyPreDefinedResource;
use App\Http\Resources\Admin\Company\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends AdminController
{
    /**
     * Show company info.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function info(Request $request)
    {
        $request->user()->company->load('locations.shifts');

        return $this->wrapResponse(
            new CompanyResource($request->user()->company)
        );
    }

    /**
     * Show company predefined fields.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function predefinedFields(Request $request)
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
            'units'
        );

        return $this->wrapResponse(
            new CompanyPreDefinedResource($request->user()->company)
        );
    }

    /**
     * Update company info.
     *
     * @param \App\Http\Requests\Admin\UpdateCompanyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function updateInfo(UpdateCompanyRequest $request)
    {
        $validated = $request->validated();
        $company = $request->user()->company;

        $company->fill($validated);

        if (!$company->save()) {
            return $this->errorResponse('Company information could not be updated.', 500);
        }

        return $this->wrapResponse(
            new CompanyResource($company)
        );
    }

    /**
     * Update company predefined fields.
     *
     * @param \App\Http\Requests\Admin\UpdateCompanyPredefinedFieldsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function updatePredefinedFields(UpdateCompanyPredefinedFieldsRequest $request)
    {
        $validated = $request->validated();
        $company = $request->user()->company;

        foreach ($validated as $field => $values) {
            $company->updatePredefinedField($field, $values);
        }

        $company->load(
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
            'units'
        );

        return $this->wrapResponse(
            new CompanyPreDefinedResource($company)
        );
    }
}
