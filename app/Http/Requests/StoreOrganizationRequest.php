<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (auth()->check()) {
            return true;
        }
        return false;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ["attribute" => "name"]),
            'name.string' => __('validation.string', ["attribute" => "name"]),
            'name.max' => __('validation.max', ["attribute" => "name", "max" => 255]),
            'description.string' => __('validation.string', ["attribute" => "description"]),
        ];
    }
}
