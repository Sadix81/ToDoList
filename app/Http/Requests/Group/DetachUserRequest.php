<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class DetachUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required', 'array'],
            'username.*' => ['required', 'exists:users,username', 'string']
        ];
    }
}
