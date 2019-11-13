<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => 'nullable|string|max:191',
            'password' => 'sometimes|string|max:191',
            'position' => 'nullable|string|max:191',
            'avatar_file_id' => 'nullable|integer|exists:files,id',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:191',
            'fax' => 'nullable|string|max:191',
            'office' => 'nullable|string|max:191',
            'skype' => 'nullable|string|max:191'
        ];
    }
}
