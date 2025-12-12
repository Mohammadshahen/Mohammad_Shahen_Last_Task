@extends('frontend.layouts.app')

@section('title', 'المدونات المفضلة')

@section('hero')
<section class="hero-section" style="background: linear-gradient(135deg, #ff6b6b 0%, #ff8e53 100%);">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">مدوناتك المفضلة</h1>
        <p class="lead mb-4">مجموعة المدونات التي أضفتها إلى قائمة المفضلة</p>
        
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card bg-white bg-opacity-25 text-white border-0">
                    <div class="card-body">
                        <h2 class="display-3 fw-bold">{{ $blogs->total() }}</h2>
                        <p class="mb-0">مدونة في المفضلة</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
@if($blogs->count() > 0)
    <div class="row">
        @foreach($blogs as $blog)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card blog-card h-100 shadow-sm">
                    <!-- صورة المدونة -->
                    @if($blog->image)
                        <img src="{{ asset('storage/' . $blog->image) }}" 
                             class="card-img-top blog-card-img" 
                             alt="{{ $blog->title }}">
                    @else
                        <div class="blog-card-img bg-light d-flex align-items-center justify-content-center">
                            <i class="fas fa-image fa-3x text-secondary"></i>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <!-- الفئات -->
                        <div class="mb-2">
                            @foreach($blog->categories->take(2) as $category)
                                <a href="{{ route('blog.index', ['category' => $category->id]) }}" 
                                   class="badge category-badge bg-primary text-decoration-none me-1">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                        
                        <!-- العنوان -->
                        <h5 class="card-title">
                            <a href="{{ route('blog.show', $blog) }}" class="text-decoration-none text-dark">
                                {{ Str::limit($blog->title, 60) }}
                            </a>
                        </h5>
                        
                        <!-- المحتوى المختصر -->
                        <p class="card-text text-muted">
                            {{ Str::limit(strip_tags($blog->content), 100) }}
                        </p>
                        
                        <!-- الإجراءات -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('blog.show', $blog) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-book-reader me-1"></i> اقرأ المزيد
                            </a>
                            
                            <div class="d-flex align-items-center">
                                <form action="{{ route('favorites.toggle', $blog) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash-alt me-1"></i> إزالة
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="far fa-calendar me-1"></i>
                                {{ $blog->created_at->diffForHumans() }}
                            </small>
                            <small class="text-primary">
                                <i class="far fa-eye me-1"></i>
                                {{ $blog->views }} مشاهدة
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        <nav aria-label="Page navigation">
            {{ $blogs->links() }}
        </nav>
    </div>
@else
    <div class="text-center py-5">
        <div class="display-1 text-muted mb-4">
            <i class="far fa-heart"></i>
        </div>
        <h3 class="text-muted mb-3">لا توجد مدونات في المفضلة</h3>
        <p class="text-muted mb-4">ابدأ بإضافة المدونات التي تعجبك إلى قائمة المفضلة</p>
        <a href="{{ route('blog.index') }}" class="btn btn-primary">
            <i class="fas fa-newspaper me-2"></i>تصفح المدونات
        </a>
    </div>
@endif

<!-- نصيحة -->
<div class="card shadow-sm mt-5">
    <div class="card-body bg-light">
        <div class="row align-items-center">
            <div class="col-md-2 text-center">
                <i class="fas fa-lightbulb fa-3x text-warning"></i>
            </div>
            <div class="col-md-10">
                <h5 class="mb-2">نصيحة مفيدة</h5>
                <p class="mb-0">
                    إضافة المدونات إلى المفضلة يساعدك على الوصول السريع إليها لاحقاً. 
                    يمكنك إضافة أي مدونة إلى المفضلة بالضغط على زر القلب <i class="fas fa-heart text-danger"></i> 
                    أثناء تصفح المدونات.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection