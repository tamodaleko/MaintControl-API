<?php

namespace App\Http\Requests\Admin;

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
            'username' => 'sometimes|string|max:191|unique:users,username,' . $this->route('user')->id,
            'password' => 'sometimes|string|max:191',
            'roles' => 'sometimes|array',
            'position' => 'nullable|string|max:191',
            'avatar_file_id' => 'nullable|integer|exists:files,id',
            'skill_id' => 'nullable|integer|exists:skills,id',
            'currency_id' => 'nullable|integer|exists:currencies,id',
            'rate' => 'nullable|numeric'
        ];
    }
}
