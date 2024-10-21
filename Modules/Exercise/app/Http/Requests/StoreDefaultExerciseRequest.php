<?php

namespace Modules\Exercise\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreDefaultExerciseRequest extends FormRequest
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
                'max:255',
                Rule::unique('default_exercises')->whereNull('deleted_at'), // Check uniqueness only on non-deleted rows
            ],
            'description' => 'nullable|string|max:1000',
            'strength_percentage' => [
                'required',          // Ensure it's required
                'numeric',          // Ensure it's a number
                'between:0,100',    // Ensure it falls between 0 and 100
            ],
            'muscle_id' => [
                'required',
                'integer', // Ensure it's an integer
                Rule::exists('muscles', 'id')->whereNull('deleted_at'), // Ensure it exists in the muscle_categories table and is not soft-deleted            ],
            ]
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
