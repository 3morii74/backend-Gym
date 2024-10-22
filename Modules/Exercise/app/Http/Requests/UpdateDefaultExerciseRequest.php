<?php

namespace Modules\Exercise\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateDefaultExerciseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [
            'id' => [
                'required',
                'integer',
                Rule::exists('default_exercises')->where(function ($query) {
                    $query->whereNull('deleted_at'); // Ensure the ID exists only in non-deleted rows
                }),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('default_exercises')
                    ->whereNull('deleted_at')
                    ->ignore($this->id), // Ignore the current record based on the id from the request
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000', // Optional field with a max length of 1000 characters.
            ],
            'strength_percentage' => [
                'required',
                'numeric', // Ensure strength_percentage is a valid number.
                'min:0', // Should be between 0 and 100.
                'max:100',
            ],
            'muscle_id' => [
                'required',
                'integer', // Ensure muscle_id is an integer.
                Rule::exists('muscles', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at'); // Ensure the ID exists only in non-deleted rows
                }),
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
