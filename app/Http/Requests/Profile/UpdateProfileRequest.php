<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'fullname' => ['nullable', 'max:100', 'string' ,  Rule::unique('users')->ignore($this->user)],
            'username' => ['nullable', 'max:100', 'string', Rule::unique('users')->ignore($this->user)],
            'mobile' => ['nullable', 'regex:/[0-9]{10}/', 'digits:11', Rule::unique('users')->ignore($this->user)],
            // 'password' => ['nullable', 'min:8' , 'regex:/[0-9]/', 'regex:/[A-Z]/', 'regex:/[a-z]/'],
            'email' => ['nullable' , 'email' , 'unique:users,email' ,  Rule::unique('users')->ignore($this->user)],
            'avatar' => ['nullable', 'string'],
        ];
    }
}
