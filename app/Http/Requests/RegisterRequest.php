<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'name' => ['required', 'unique:events', 'max:255'],
            'phone' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'gender' => ['required', 'in:MALE,FEMALE'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()     // Must include upper and lower case
                    ->letters()       // Must include at least one letter
                    ->numbers()       // Must include at least one number
                    ->symbols()       // Must include at least one symbol
                    ->uncompromised(), // Not in known data breaches
            ],
        ];
    }
}
