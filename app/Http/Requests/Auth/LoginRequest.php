<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => ['nullable', 'exists:users,username'],
            'email' => ['nullable',  'exists:users,email'],
            'password' => ['required'],
        ];
    }
}
