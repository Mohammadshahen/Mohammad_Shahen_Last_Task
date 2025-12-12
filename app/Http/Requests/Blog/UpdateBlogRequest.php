<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogRequest extends FormRequest
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
        $blogId = $this->route('blog')->id ?? null;
        return [
            'title' => [
                'nullable',
                'string',
                'max:255',
                'min:3',
                Rule::unique('blogs')->ignore($blogId),
            ],
            'content' => [
                'nullable',
                'string',
                'min:10',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
                'dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000',
            ],
            'categories' => [
                'nullable',
                'array',
                'min:1',
                'max:5',
            ],
            'categories.*' => [
                'nullable',
                'integer',
                'exists:categories,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'عنوان المدونة يجب أن يكون نصاً',
            'title.max' => 'عنوان المدونة لا يمكن أن يزيد عن 255 حرفاً',
            'title.min' => 'عنوان المدونة يجب أن يكون على الأقل 3 أحرف',
            'title.unique' => 'هذا العنوان مستخدم بالفعل',
            
            'content.string' => 'محتوى المدونة يجب أن يكون نصاً',
            'content.min' => 'محتوى المدونة يجب أن يكون على الأقل 10 أحرف',
            
            'image.image' => 'الملف المرفوع يجب أن يكون صورة',
            'image.mimes' => 'صيغ الصور المسموحة: JPEG, PNG, JPG, GIF, WEBP',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 5 ميجابايت',
            'image.dimensions' => 'أبعاد الصورة يجب أن تكون بين 100×100 و 4000×4000 بكسل',
            
            'categories.array' => 'الفئات يجب أن تكون مصفوفة',
            'categories.min' => 'يجب اختيار فئة واحدة على الأقل',
            'categories.max' => 'لا يمكن اختيار أكثر من 5 فئات',
            
            'categories.*.integer' => 'معرف الفئة يجب أن يكون رقماً',
            'categories.*.exists' => 'الفئة المحددة غير موجودة',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'عنوان المدونة',
            'content' => 'محتوى المدونة',
            'image' => 'صورة المدونة',
            'categories' => 'الفئات',
            'categories.*' => 'الفئة',
        ];
    }
}
