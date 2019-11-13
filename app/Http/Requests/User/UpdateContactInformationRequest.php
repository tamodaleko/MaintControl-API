<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactInformationRequest extends FormRequest
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
            'email' => 'sometimes|string|max:191',
            'phone' => 'sometimes|string|max:191',
            'fax' => 'sometimes|string|max:191',
            'office' => 'sometimes|string|max:191',
            'skype' => 'sometimes|string|max:191'
        ];
    }
}
