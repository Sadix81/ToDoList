<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'description' => ['nullable' , 'string' , 'max:255'],
            'task_id' => ['required' , 'exists:tasks,id', 'integer', 'gt:0'],
        ];
    }
}
