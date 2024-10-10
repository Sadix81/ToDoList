<?php

namespace App\Http\Requests\Subtask;

use Illuminate\Foundation\Http\FormRequest;

class CreateSubtaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required' , 'string' , 'max:100'],
            'description' => ['nullable' , 'string' , 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,bmp,gif,svg,webp', 'max:5048'], // Max size 5MB
            'status' => ['nullable', 'in:0,1'],
            'task_id' => ['required', 'exists:tasks,id', 'integer', 'gt:0'],
            'user_id' => ['required', 'exists:users,id', 'integer', 'gt:0'],
        ];
    }
}
