<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Blog extends Model
{
    
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'content', 'image'];

    // علاقة مع الفئات
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'blog_category');
    }
    /**
     * علاقة many-to-many مع المستخدمين (المفضلة)
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    /**
     * التحقق إذا كان المدونة مفضلة للمستخدم الحالي
     */
    public function isFavoritedBy($userId = null)
    {
        if (!$userId && Auth::check()) {
            $userId = Auth::id();
        }
        
        if (!$userId) {
            return false;
        }
        
        // استخدام exists() للتحقق السريع
        return $this->favoritedBy()
            ->where('user_id', $userId)
            ->exists();
    }
}
