<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class CreateGroupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'owner_id' => ['nullable', 'integer', 'exists:users,id'],
            'user_id' => ['nullable', 'array'],
            'user_id.*' => ['nullable', 'exists:users,id', 'string'], 
        ];
    }
}
