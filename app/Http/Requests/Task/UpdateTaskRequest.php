<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function rules(): array
    {
        // Get the ID of the authenticated user
        $ownerId = $this->user()->id;

        return [
            'title' => ['required', 'string', 'max:100', Rule::unique('tasks')->where(function ($query) use ($ownerId) {
                return $query->where('owner_id', $ownerId);
            })->ignore($this->task),
            ],
            'priority' => ['required', 'in:1,2,3'], //سه بالاترین درجه و اولویت بیشتری داره
            'description' => ['nullable', 'string', 'max:255'],
            'started_at' => ['nullable', 'date'],
            'finished_at' => ['nullable', 'date'],
            'status' => ['nullable', 'integer', 'in:0,1'],
            'image' => ['nullable', 'mimes:jpg,jpeg,png,bmp,gif,svg,webp', 'max:5048'], // Max size 2MB
            'category_id' => ['nullable', 'array'],
            'category_id.*' => ['nullable', 'exists:categories,id', 'integer'],
            'group_id' => ['nullable', 'exists:groups,id', 'integer', 'gt:0'],
        ];
    }
}
