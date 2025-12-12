<?php
// app/Http\Controllers\Frontend\BlogController.php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // عرض جميع المدونات
    public function index(Request $request)
    {
        $query = Blog::with('categories');
        
        // التصفية بالفئة
        if ($request->has('category') && $request->category != 'all') {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('id', $request->category);
            });
        }
        
        // البحث
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }
        
        $blogs = $query->latest()->paginate(12);
        $categories = Category::withCount('blogs')->get();
        
        return view('frontend.blogs.index', compact('blogs', 'categories'));
    }

    // عرض تفاصيل المدونة
    public function show(Blog $blog)
    {
        $blog->load('categories');
        
        // زيادة عدد المشاهدات
        $blog->increment('views', 1);
        
        // المدونات المشابهة
        $relatedBlogs = Blog::whereHas('categories', function ($query) use ($blog) {
            $query->whereIn('categories.id', $blog->categories->pluck('id'));
        })
        ->where('id', '!=', $blog->id)
        ->with('categories')
        ->latest()
        ->take(4)
        ->get();
        
        // المدونات الأكثر مشاهدة
        $popularBlogs = Blog::with('categories')
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();
        
        return view('frontend.blogs.show', compact('blog', 'relatedBlogs', 'popularBlogs'));
    }
}