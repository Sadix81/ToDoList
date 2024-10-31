<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'fullname' => ['required', 'max:100', 'string'],
            'username' => ['required', 'max:100', 'string', 'unique:users,username'],
            'mobile' => ['required', 'regex:/[0-9]{10}/', 'digits:11', 'unique:users,mobile'],
            'password' => ['required', 'min:8', 'regex:/[0-9]/', 'regex:/[A-Z]/', 'regex:/[a-z]/'],

            'email' => ['required', 'email', 'unique:users,email'],
            'email_verified_at' => ['nullable'],
            'avatar' => ['nullable', 'image'],
        ];
    }
}
