<?php

namespace App\Http\Requests\ForgotPassword;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => ['required', 'min:8' , 'regex:/[0-9]/', 'regex:/[A-Z]/', 'regex:/[a-z]/'],
            'confirmpassword' => ['required', 'min:8' , 'regex:/[0-9]/', 'regex:/[A-Z]/', 'regex:/[a-z]/'],            
        ];
    }
}
