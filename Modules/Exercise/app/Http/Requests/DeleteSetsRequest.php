<?php

namespace Modules\Exercise\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Modules\Exercise\Models\Set;

class DeleteSetsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'id' => [
                'required',
                Rule::exists('sets', 'id')->whereNull('deleted_at'), // Ensure the set exists and is not soft deleted
            ],
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->whereNull('deleted_at'), // Ensure the user is not soft deleted
                Rule::in([$this->getUserIdFromSet($this->id)]) // Ensure the user_id is the same as the one in the set
            ],
        ];
    }
    protected function getUserIdFromSet($setId)
    {
        return Set::where('id', $setId)->whereNull('deleted_at')->value('user_id'); // Fetch the user_id only if the set is not deleted
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
