<?php

namespace Modules\Location\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateNationalityRequest extends FormRequest
{



    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'id'=> 'exists:nationalities,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive', // Adjust the statuses as per your application
            // Add any other fields you want to validate
        ];
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
            'status'=>'405',
        ], 405));
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if the authenticated user has the "store" role or permission
        $user = Auth::user();
        return $user && $user->can('update', 'api');
    }
}
