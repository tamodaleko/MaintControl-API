<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSpareRequest extends FormRequest
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
            'uid' => 'sometimes|string|max:191',
            'name' => 'sometimes|string|max:191',
            'stock' => 'sometimes|string|max:191',
            'manufacturer' => 'sometimes|string|max:191',
            'vendor' => 'sometimes|string|max:191',
            'amount' => 'sometimes|numeric',
            'unit_id' => 'nullable|integer|exists:units,id',
            'currency_id' => 'sometimes|integer|exists:currencies,id',
            'assets' => 'sometimes|array'
        ];
    }
}
