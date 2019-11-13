<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequest extends FormRequest
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
            'uid' => 'sometimes|string|max:191',
            'area_id' => 'sometimes|integer|exists:areas,id',
            'category_id' => 'nullable|integer|exists:asset_categories,id',
            'condition_id' => 'nullable|integer',
            'avatar_file_id' => 'nullable|integer|exists:files,id',
            'meter_readings' => 'nullable|integer',
            'location_description' => 'nullable|string|max:191',
            'manufacturer' => 'nullable|string|max:191',
            'model' => 'nullable|string|max:191',
            'serial' => 'nullable|string|max:191',
            'installed' => 'nullable|string|max:191',
            'spares' => 'sometimes|array',
            'assets' => 'sometimes|array'
        ];
    }
}
