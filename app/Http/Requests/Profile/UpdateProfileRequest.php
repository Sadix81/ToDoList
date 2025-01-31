<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'fullname' => ['nullable', 'max:100', 'string', Rule::unique('users')->ignore($this->user)],
            'username' => ['nullable', 'max:100', 'string', Rule::unique('users')->ignore($this->user)],
            'mobile' => ['nullable', 'regex:/[0-9]{10}/', 'digits:11', Rule::unique('users')->ignore($this->user)],
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($this->user)],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,bmp,gif,svg,webp', 'max:5048'], // Max size 5MB

        ];
    }
}
