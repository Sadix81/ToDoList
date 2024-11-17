<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function rules(): array
    {
        $ownerId = $this->user()->id; // Get the ID of the authenticated user

        return [
            'title' => ['required','string','max:100',Rule::unique('tasks')->where(function ($query) use ($ownerId) {
                    return $query->where('owner_id', $ownerId);
                })->ignore($this->task) 
            ],
            'priority' => ['required', 'in:1,2,3,4'],
            'description' => ['nullable', 'string', 'max:255'],
            'started_at' => ['nullable', 'date'],
            'finished_at' => ['nullable', 'date'],
            'status' => ['nullable', 'integer' , 'in:0,1'],
            'image' => ['nullable', 'mimes:jpg,jpeg,png,bmp,gif,svg,webp', 'max:2048'], // Max size 2MB
            'category_id' => ['required', 'array'],
            'category_id.*' => ['required', 'exists:categories,id', 'integer', 'gt:0'],
            // 'user_id' => ['nullable' , 'exists:users,id' , 'integer' , 'gt:0'],
            'group_id' => ['nullable', 'exists:groups,id' , 'integer' , 'gt:0'],
        ];
    }
}
