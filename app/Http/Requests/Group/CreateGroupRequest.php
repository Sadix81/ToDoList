<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class CreateGroupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50' , 'unique:groups,name'],
            'user_id' => ['nullable', 'array'],
            'user_id.*' => ['nullable', 'exists:users,id', 'integer', 'gt:0'],
        ];
    }
}
