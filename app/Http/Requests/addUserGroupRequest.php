<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addUserGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()->check()) {
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
            "organization_id" => "required|exists:organizations,id",
            'user_id' => 'required|exists:users,id',
            'second_user_id' => 'required|exists:users,id',
            'group_id' => 'required|exists:organization_groups,id'
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => __("validation.required", ["attribute" => "user_id"]),
            'user_id.exists' => __("validation.exists", ["attribute" => "user_id"]),
            'second_user_id.required' => __("validation.required", ["attribute" => "second_user_id"]),
            'second_user_id.exists' => __("validation.exists", ["attribute" => "second_user_id"]),
            'group_id.required' => __("validation.required", ["attribute" => "group_id"]),
            'group_id.exists' => __("validation.exists", ["attribute" => "group_id"]),
        ];
    }
}
