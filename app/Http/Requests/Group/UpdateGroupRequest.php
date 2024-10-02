<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50', Rule::unique('groups')->ignore($this->group)],
            'user_id' => ['nullable', 'array'],
            'user_id.*' => ['nullable', 'exists:users,id', 'integer', 'gt:0'],
        ];
    }
}
