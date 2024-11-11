<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordrequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'currentpassword' => ['required', 'min:8' , 'regex:/[0-9]/', 'regex:/[A-Z]/', 'regex:/[a-z]/'],
            'newpassword' => ['required', 'min:8' , 'regex:/[0-9]/', 'regex:/[A-Z]/', 'regex:/[a-z]/'],

        ];
    }
}
