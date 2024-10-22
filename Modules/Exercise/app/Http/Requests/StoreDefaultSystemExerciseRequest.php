<?php

namespace Modules\Exercise\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreDefaultSystemExerciseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [
            'name' => [
                'required',
                'string',
                'max:255', // Limit the maximum length of the name

            ],
            'description' => [
                'nullable',
                'string',
                'max:1000', // Limit the maximum length of the description
            ],
            'default_exerciseId' => [
                'required', // Ensure this field is required
                Rule::exists('default_exercises', 'id')->whereNull('deleted_at'),
            ],
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
            'status' => '422',

        ], 422));
    }
}
