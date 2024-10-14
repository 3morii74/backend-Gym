<?php

namespace Modules\Location\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:cities,id', // Ensures 'id' is a valid state ID
            'name' => 'required|string|max:255', // 'name' must be unique in 'states' table
            'status' => 'required|in:active,inactive', // 'status' should either be 'active' or 'inactive'
            'state_id' => 'required|integer|exists:states,id', // 'country_id' must be a valid country ID
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed!',
            'errors' => $errors,
            'status' => '405',
        ], 405));
    }
}
