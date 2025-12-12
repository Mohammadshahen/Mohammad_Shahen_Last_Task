<?php
// app/Http\Controllers\Frontend\FavoriteController.php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // عرض المدونات المفضلة
    public function index()
    {
        $user = Auth::user();
        $blogs = $user->favoriteBlogs()
            ->with('categories')
            ->latest()
            ->paginate(12);
        
        return view('frontend.blogs.favorites', compact('blogs'));
    }

    // إضافة/إزالة من المفضلة
    public function toggle(Blog $blog, Request $request)
    {
        $user = Auth::user();
        
        if ($user->favoriteBlogs()->where('blog_id', $blog->id)->exists()) {
            $user->favoriteBlogs()->detach($blog->id);
            $isFavorited = false;
            $message = 'تمت إزالة المدونة من المفضلة';
        } else {
            $user->favoriteBlogs()->attach($blog->id);
            $isFavorited = true;
            $message = 'تمت إضافة المدونة إلى المفضلة';
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_favorited' => $isFavorited,
                'favorites_count' => $user->favoriteBlogs()->count()
            ]);
        }
        
        return back()->with('success', $message);
    }

    // حذف من المفضلة
    public function destroy(Blog $blog)
    {
        Auth::user()->favoriteBlogs()->detach($blog->id);
        
        return redirect()->route('favorites.index')
            ->with('success', 'تمت إزالة المدونة من المفضلة');
    }
}