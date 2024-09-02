<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $rules = [
            'password' => 'required|min:8',
            'firstName' => 'required|between:4,20',
            'lastName' => 'sometimes|between:4,10',
            'phone' => 'required|between:8,20',
            'email' => '',
        ];

        if ($this->route()->getName() === 'user.register') {
            $rules['email'] = 'required|email|unique:users,email';
        }

        return $rules;
    }
}
