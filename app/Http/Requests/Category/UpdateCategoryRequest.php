<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required' , 'string' , 'max:50' , Rule::unique('categories')->ignore($this->category)]
        ];
    }
}
