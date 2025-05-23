<?php

namespace App\Interfaces\Api\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class SubscribeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc,dns', 'min:5', 'max:254'], // Updated min to 5 (a@b.c) and max to 254 per RFC
            'city' => ['required', 'string', 'min:2', 'max:50'],
            'frequency' => ['required', Rule::in(['daily', 'hourly'])],
        ];
    }

    /**
     * Custom error messages for validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email address is required',
            'email.email' => 'Please provide a valid email address',
            'email.min' => 'Email must be at least :min characters',
            'email.max' => 'Email cannot exceed :max characters',

            'city.required' => 'City name is required',
            'city.string' => 'City name must be a string',
            'city.min' => 'City name must be at least :min characters',
            'city.max' => 'City name cannot exceed :max characters',

            'frequency.required' => 'Weather update frequency is required',
            'frequency.in' => 'Frequency must be either "daily" or "hourly"',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST)
        );
    }
}
