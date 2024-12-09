<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class DetachUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'array'],
            'user_id.*' => ['nullable', 'exists:users,id', 'string'],
        ];
    }
}
