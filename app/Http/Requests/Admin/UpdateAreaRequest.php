<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAreaRequest extends FormRequest
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
            'location_id' => 'sometimes|integer|exists:locations,id',
            'users' => 'sometimes|array',
            'workgroups' => 'sometimes|array',
            'assets' => 'sometimes|array'
        ];
    }
}
