<?php
// app/Http\Controllers/Admin/CategoryController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // عرض جميع الفئات
    public function index(Request $request)
    {
        
        $categories = Category::withCount('blogs')
            ->latest()
            ->paginate(10);
            
        return view('admin.categories.index', compact('categories'));
    }

    // عرض نموذج إنشاء فئة
    public function create()
    {
        return view('admin.categories.create');
    }

    // حفظ الفئة الجديدة
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        Category::create([
            'name' => $data['name'],
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'تم إنشاء الفئة بنجاح');
    }

    // عرض الفئة
    public function show(Category $category)
    {
        $category->load(['blogs' => function ($query) {
            $query->with('categories')->latest();
        }]);
        
        return view('admin.categories.show', compact('category'));
    }

    // عرض نموذج تعديل الفئة
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // تحديث الفئة
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        $category->update([
            'name' => $data['name'] ?? $category->name,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'تم تحديث الفئة بنجاح');
    }

    // حذف الفئة
    public function destroy(Category $category)
    {
        // التحقق إذا كانت الفئة مرتبطة بمدونات
        if ($category->blogs()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف الفئة لأنها مرتبطة بمدونات');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'تم حذف الفئة بنجاح');
    }
}