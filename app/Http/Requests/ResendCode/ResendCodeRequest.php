<?php

namespace App\Http\Requests\ResendCode;

use Illuminate\Foundation\Http\FormRequest;

class ResendCodeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'exists:users,email', 'string'],
        ];
    }
}
