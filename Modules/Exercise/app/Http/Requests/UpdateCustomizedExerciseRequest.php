<?php

namespace Modules\Exercise\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Exercise\Models\CustomizedExercise;

class UpdateCustomizedExerciseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $exercise = CustomizedExercise::find($this->id);
        return [
            'id' => [
                'required',
                'integer',
                Rule::exists('customized_exercises')->where(function ($query) {
                    $query->whereNull('deleted_at'); // Ensure the ID exists only in non-deleted rows
                }),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('customized_exercises')
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
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id'), // Ensure it exists in the users table
                function ($attribute, $value, $fail) use ($exercise) {
                    // Check if the user_id in the request matches the one in the exercise record
                    if ($exercise && $exercise->user_id !== $this->user_id) {
                        $fail('The provided user_id does not match the owner of the exercise.');
                    }
                },
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
