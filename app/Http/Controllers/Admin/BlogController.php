<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\blog\StoreBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    // عرض جميع المدونات
    public function index()
    {
        $blogs = Blog::with('categories')->latest()->paginate(10);
        return view('admin.blogs.index', compact('blogs'));
    }

    // عرض نموذج الإنشاء
    public function create()
    {
        $categories = Category::select('name','id')->get();
        return view('admin.blogs.create', compact('categories'));
    }

    // حفظ المدونة
    public function store(StoreBlogRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('blogs', 'public');
            }
            // return $data;

            $blog = Blog::create([
                'title' => $data['title'],
                'content' => $data['content'],
                'image' => $data['image'] ?? null,
                
            ]);
            $blog->categories()->sync($data['categories']);

            DB::commit();

            return redirect()->route('blogs.index')
                ->with('success', 'تم إنشاء المدونة بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء المدونة: ' . $e->getMessage());
        }
    }

    // عرض المدونة
    public function show(Blog $blog)
    {
        return view('admin.blogs.show', compact('blog'));
    }

    // عرض نموذج التعديل
    public function edit(Blog $blog)
    {
        $categories = Category::select('name','id')->get();
        $blog->load('categories');
        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    // تحديث المدونة
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        try {

            DB::beginTransaction();

            $data = $request->validated();
            
            if ($request->hasFile('image')) {
                if ($blog->image) {
                    Storage::disk('public')->delete($blog->image);
                }
                $data['image'] = $request->file('image')->store('blogs', 'public');
            }

            $blog->update([
                'title' => $data['title'] ?? $blog->title,
                'content' => $data['content'] ?? $blog->content,
                'image' => $data['image'] ?? $blog->image,
            ]);
            $blog->categories()->sync($data['categories'] ?? $blog->categories);

            DB::commit();

            return redirect()->route('blogs.index')
                ->with('success', 'تم تحديث المدونة بنجاح');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تعديل المدونة: ' . $e->getMessage());
        }
    }

    // حذف المدونة (Soft Delete)
    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('blogs.index')
            ->with('success', 'تم نقل المدونة إلى سلة المهملات');
    }

    // عرض سلة المهملات
    public function trash()
    {
        $blogs = Blog::onlyTrashed()->latest()->paginate(10);
        return view('admin.blogs.trash', compact('blogs'));
    }

    // استعادة المدونة
    public function restore($id)
    {
        $blog = Blog::onlyTrashed()->findOrFail($id);
        $blog->restore();
        
        return redirect()->route('blogs_trash')
            ->with('success', 'تم استعادة المدونة بنجاح');
    }

    // حذف نهائي
    public function forceDelete($id)
    {
        $blog = Blog::onlyTrashed()->findOrFail($id);
        
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }
        
        $blog->forceDelete();
        
        return redirect()->route('blogs_trash')
            ->with('success', 'تم حذف المدونة نهائياً');
    }
}