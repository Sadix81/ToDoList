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
            'user_id' => ['nullable', 'array'],
            'user_id.*' => ['nullable', 'exists:users,id', 'string'],
        ];
    }
}
