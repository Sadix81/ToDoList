<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class SearchUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => ['nullable', 'string' , 'exists:users,username'],
        ];
    }
}
