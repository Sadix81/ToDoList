<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'priority' => ['required', 'in:1,2,3'],
            'description' => ['nullable', 'string', 'max:255'],
            'started_at' => ['nullable', 'date'],
            'finished_at' => ['nullable', 'date'],
            'status' => ['nullable', 'in:0,1'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,bmp,gif,svg,webp', 'max:5048'], // Max size 5MB
            'category_id' => ['required', 'array'],
            'category_id.*' => ['required', 'exists:categories,id', 'integer', 'gt:0'],
            // 'user_id' => ['nullable' , 'exists:users,id' , 'integer' , 'gt:0'],
            'group_id' => ['nullable', 'exists:groups,id' , 'integer' , 'gt:0'],
        ];
    }
}
