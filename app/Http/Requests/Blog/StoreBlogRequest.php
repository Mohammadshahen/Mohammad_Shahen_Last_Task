<?php
// app/Http/Requests/BlogStoreRequest.php
namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // تأكد من تغيير هذا حسب نظام الصلاحيات
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'unique:blogs,title',
                                                                               
            ],
            'content' => [
                'required',
                'string',
                'min:10',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120', // 5MB
                'dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000',
            ],
            'categories' => [
                'required',
                'array',
                'min:1',
                'max:5',
            ],
            'categories.*' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان المدونة مطلوب',
            'title.string' => 'عنوان المدونة يجب أن يكون نصاً',
            'title.max' => 'عنوان المدونة لا يمكن أن يزيد عن 255 حرفاً',
            'title.min' => 'عنوان المدونة يجب أن يكون على الأقل 3 أحرف',
            'title.unique' => 'العنوان موجود بالفعل',
            
            'content.required' => 'محتوى المدونة مطلوب',
            'content.string' => 'محتوى المدونة يجب أن يكون نصاً',
            'content.min' => 'محتوى المدونة يجب أن يكون على الأقل 10 أحرف',
            
            'image.image' => 'الملف المرفوع يجب أن يكون صورة',
            'image.mimes' => 'صيغ الصور المسموحة: JPEG, PNG, JPG, GIF, WEBP',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 5 ميجابايت',
            'image.dimensions' => 'أبعاد الصورة يجب أن تكون بين 100×100 و 4000×4000 بكسل',
            
            'categories.required' => 'يجب اختيار فئة واحدة على الأقل',
            'categories.array' => 'الفئات يجب أن تكون مصفوفة',
            'categories.min' => 'يجب اختيار فئة واحدة على الأقل',
            'categories.max' => 'لا يمكن اختيار أكثر من 5 فئات',
            
            'categories.*.required' => 'كل فئة يجب أن تكون صحيحة',
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