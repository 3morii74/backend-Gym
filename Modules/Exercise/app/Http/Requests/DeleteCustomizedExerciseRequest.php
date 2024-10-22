<?php

namespace Modules\Exercise\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Modules\Exercise\Models\CustomizedExercise;

class DeleteCustomizedExerciseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $exercise = CustomizedExercise::find($this->id);

        return [
            'id' => 'required|integer|exists:customized_exercises,id', // Validate that id exists in countries table
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
            'status' => '405',
        ], 405));
    }
}