<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyPredefinedFieldsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Has to be changed
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'priorities' => 'sometimes|array',
            'skills' => 'sometimes|array',
            'permits' => 'sometimes|array',
            'criticals' => 'sometimes|array',
            'activityTypesCO' => 'sometimes|array',
            'activityTypesPO' => 'sometimes|array',
            'additionalFieldsCO' => 'sometimes|array',
            'additionalFieldsPO' => 'sometimes|array',
            'assetCategories' => 'sometimes|array',
            'conditions' => 'sometimes|array',
            'units' => 'sometimes|array'
        ];
    }
}
