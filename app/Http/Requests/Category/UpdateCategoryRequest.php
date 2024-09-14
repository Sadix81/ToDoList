<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required' , 'string' , 'max:50' , 'unique:categories,title']
        ];
    }
}
