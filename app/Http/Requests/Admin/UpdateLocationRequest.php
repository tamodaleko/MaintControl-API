<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLocationRequest extends FormRequest
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
            'name' => 'sometimes|string|max:191',
            'country_id' => 'nullable|integer|exists:countries,id',
            'city' => 'nullable|string|max:191',
            'address' => 'nullable|string|max:191',
            'phone' => 'nullable|string|max:191',
            'fax' => 'nullable|string|max:191',
            'email' => 'nullable|string|max:191',
            'time_format_24' => 'nullable|integer',
            'workdays' => 'nullable|array',
            'manager_id' => 'nullable|integer|exists:users,id',
            'timezone_id' => 'nullable|integer|exists:timezones,id',
            'currency_id' => 'nullable|integer|exists:currencies,id'
        ];
    }
}
