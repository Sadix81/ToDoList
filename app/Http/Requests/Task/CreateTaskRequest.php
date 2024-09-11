<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'started_at' => ['nullable' , 'date'],
            'finished_at' => ['nullable' , 'date'],
            'priority' => ['nullable' , 'in:1,2,3,4'],
            'reminder'=> ['nullable'],
            'label' => ['nullable' , 'string' , 'max:50'],
            'status' => ['required' , 'max:1' , 'min:0'],
            // 'storage_id',
        ];
    }
}
