<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $categoryId = $this->route('category')->id ?? null;
        return [
            'name' => [
                'nullable',
                'string',
                'max:255',
                'min:2',
                Rule::unique('categories')->ignore($categoryId),
            ],
        ];
    }


     public function messages(): array
    {
        return [
            'name.string' => 'اسم الفئة يجب أن يكون نصاً',
            'name.max' => 'اسم الفئة لا يمكن أن يزيد عن 255 حرفاً',
            'name.min' => 'اسم الفئة يجب أن يكون على الأقل حرفين',
            'name.unique' => 'هذه الفئة موجودة مسبقاً',
        ];
    }
}
