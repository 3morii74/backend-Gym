<?php

namespace Modules\Location\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNationalityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:nationalities,name',
            'status' => 'required|in:active,inactive',
        ];
    }

    /**
     * Customize the error messages for validation.
     *
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The nationality name is required.',
            'name.unique' => 'The nationality name must be unique.',
            'status.required' => 'The status is required.',
            'status.in' => 'The status must be either active or inactive.',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
