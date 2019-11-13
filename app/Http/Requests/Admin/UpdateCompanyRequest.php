<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
            'owner_id' => 'sometimes|integer|exists:users,id',
            'name' => 'sometimes|string|max:191|unique:companies,name,' . $this->user()->company->id,
            'logo_file_id' => 'nullable|integer|exists:files,id',
            'slogan' => 'nullable|string|max:191'
        ];
    }
}
