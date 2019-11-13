<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateCurrencyRequest;
use App\Http\Resources\Admin\CurrencyResource;
use App\Http\Resources\Admin\CurrencyResourceCollection;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends AdminController
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
            new CurrencyResourceCollection($request->user()->company->currencies)
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
        $validated = $request->validate(Currency::$rules);
        $validated['company_id'] = $request->user()->company_id;

        $currency = Currency::create($validated);

        if (!$currency) {
            return $this->errorResponse('Currency could not be created.', 500);
        }

        return $this->wrapResponse(new CurrencyResource(Currency::find($currency->id)));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        return $this->wrapResponse(new CurrencyResource($currency));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Admin\UpdateCurrencyRequest $request
     * @param \App\Models\Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCurrencyRequest $request, Currency $currency)
    {
        $validated = $request->validated();
        $validated['company_id'] = $request->user()->company_id;

        $currency->fill($validated);

        if (!$currency->save()) {
            return $this->errorResponse('Currency could not be updated.', 500);
        }

        return $this->wrapResponse(new CurrencyResource(Currency::find($currency->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Currency $currency)
    {
        if (!$currency->delete()) {
            return $this->errorResponse('Currency could not be deleted.', 500);
        }

        return $this->successResponse('Currency has been deleted successfully.');
    }
}
