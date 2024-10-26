<?php

namespace Modules\Exercise\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreSetsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::exists('users', 'id')->whereNull('deleted_at'), // Ensure the user exists and is not soft deleted
            ],
            'user_exercise_id' => [
                'required',
                'exists:user_exercises,id',
                // Custom validation to check if user_id matches the one in user_exercises
                function ($attribute, $value, $fail) {
                    $userId = $this->input('user_id'); // Get the user_id from the request

                    // Check if the user_exercise_id corresponds to the user_id
                    $exists = DB::table('user_exercises')
                        ->where('id', $value)
                        ->where('user_id', $userId)
                        ->exists();

                    if (!$exists) {
                        $fail('The selected user_exercise_id is invalid for the specified user_id.');
                    }
                },
                // Check if user_exercise_id is unique in sets table
                Rule::unique('sets')->where(function ($query) {
                    return $query->where('user_exercise_id', $this->input('user_exercise_id'));
                })->whereNull('deleted_at'), // Include soft delete check
            ],
            'reps' => 'required|integer|min:1',
            'weight' => 'required|numeric|min:0',
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
