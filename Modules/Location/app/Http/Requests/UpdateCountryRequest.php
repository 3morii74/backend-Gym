<?php

namespace Modules\Location\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCountryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [
            'name' => 'required|string|max:255|exists:countries,name,',
            'iso_code' => 'required|string|max:10|exists:countries,iso_code,',
            'status' => 'required|in:active,inactive',
            'nationality_id' => 'required|integer|exists:nationalities,id',
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
            'errors' => $errors
        ], 422));
    }
}
