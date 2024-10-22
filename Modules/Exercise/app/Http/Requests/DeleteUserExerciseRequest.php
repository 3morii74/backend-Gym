<?php

namespace Modules\Exercise\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class DeleteUserExerciseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [
            'user_id' => 'required|exists:users,id',
            'system_id' => [
                'required',
                'integer',
                Rule::exists('exercise_system_defaults', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at'); // Ensure the ID exists only in non-deleted rows
                }),
            ],
            'exercise_ids' => 'required|array',
            'exercise_ids.*' => [
                'required',
                'exists:default_exercises,id', // Ensure all exercise IDs exist in default_exercises
                Rule::exists('user_exercises','exercise_id')->where(function ($query) {
                    $query->where('user_id', $this->user_id); // Check if it exists in user_exercises for the specific user
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
            'status' => '405',
        ], 405));
    }
}
