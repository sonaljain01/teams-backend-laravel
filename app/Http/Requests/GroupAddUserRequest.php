<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupAddUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(!auth()->check()) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'group_id' => 'required|exists:groups,id'
        ];
    }

    public function messages(): array{
        return [
            'user_id.required' => 'The user id field is required.',
            'user_id.exists' => 'The user id does not exist.',
            'group_id.required' => 'The group id field is required.',
            'group_id.exists' => 'The group id does not exist.',
        ];
    }
}
