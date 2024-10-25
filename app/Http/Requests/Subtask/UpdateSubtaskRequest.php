<?php

namespace App\Http\Requests\Subtask;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubtaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100', Rule::unique('tasks')->ignore($this->task)], //unique
            'description' => ['nullable' , 'string' , 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,bmp,gif,svg,webp', 'max:5048'], // Max size 5MB
            'status' => ['nullable', 'in:0,1'],
            'user_id' => ['nullable', 'exists:users,id', 'integer', 'gt:0'],
        ];
    }
}
