<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGroupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'username' => ['nullable', 'array'],
            'username.*' => ['nullable', 'exists:users,username', 'string'],
        ];
    }
}
