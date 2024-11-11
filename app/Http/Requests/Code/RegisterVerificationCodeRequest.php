<?php

namespace App\Http\Requests\Code;

use Illuminate\Foundation\Http\FormRequest;

class RegisterVerificationCodeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => ['required', 'integer', 'digits:5'],  
        ];
    }
}
