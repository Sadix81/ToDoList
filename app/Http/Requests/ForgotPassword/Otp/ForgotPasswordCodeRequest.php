<?php

namespace App\Http\Requests\ForgotPassword\Otp;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordCodeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => ['required', 'integer', 'digits:4'],  
        ];
    }
}
