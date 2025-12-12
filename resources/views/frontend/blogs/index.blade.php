@extends('frontend.layouts.app')

@section('title', 'المدونات')

@section('hero')
<section class="hero-section">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">أرشيف المدونات</h1>
        <p class="lead mb-4">استكشف مجموعة متنوعة من المقالات والمدونات في مختلف المجالات</p>
        
        <!-- بحث سريع -->
        <form action="{{ route('blog.index') }}" method="GET" class="row g-3 justify-content-center">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-lg" 
                           placeholder="ابحث عن موضوع معين..." value="{{ request('search') }}">
                    <button class="btn btn-light btn-lg" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('content')
<div class="row">
    <!-- Sidebar - الفئات -->
    <div class="col-lg-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i>تصفية حسب الفئة</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('blog.index') }}" 
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ !request('category') ? 'active' : '' }}">
                        جميع المدونات
                        <span class="badge bg-primary rounded-pill">{{ App\Models\Blog::count() }}</span>
                    </a>
                    
                    @foreach($categories as $category)
                        <a href="{{ route('blog.index', ['category' => $category->id]) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request('category') == $category->id ? 'active' : '' }}">
                            {{ $category->name }}
                            <span class="badge bg-secondary rounded-pill">{{ $category->blogs_count }}</span>
                        </a>
                    @endforeach
                </div>
                
                <!-- إحصائيات -->
                <div class="mt-4 pt-3 border-top">
                    <h6 class="text-muted mb-3"><i class="fas fa-chart-bar me-2"></i>إحصائيات</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <small>المدونات المعروضة:</small>
                        <small class="text-primary">{{ $blogs->total() }}</small>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <small>عدد الفئات:</small>
                        <small class="text-success">{{ $categories->count() }}</small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small>آخر تحديث:</small>
                        <small class="text-info">{{ now()->format('Y-m-d') }}</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- المدونات الأكثر مشاهدة -->
        @php
            $popularBlogs = App\Models\Blog::with('categories')
            ->orderBy('views', 'desc')  
            ->take(3)                    
            ->get();
        @endphp
        
        @if($popularBlogs->count() > 0)
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-fire me-2"></i>الأكثر مشاهدة</h5>
                </div>
                <div class="card-body">
                    @foreach($popularBlogs as $blog)
                        <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <h6 class="mb-1">
                                <a href="{{ route('blog.show', $blog) }}" class="text-decoration-none">
                                    {{ Str::limit($blog->title, 40) }}
                                </a>
                            </h6>
                            <small class="text-muted d-block mb-1">
                                <i class="far fa-eye me-1"></i> {{ $blog->views }} مشاهدة
                            </small>
                            <small class="text-muted">
                                <i class="far fa-calendar me-1"></i> {{ $blog->created_at->diffForHumans() }}
                            </small>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    
    <!-- محتوى المدونات -->
    <div class="col-lg-9">
        <!-- ترتيب وفلترة -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">
                            <i class="fas fa-newspaper me-2 text-primary"></i>
                            المدونات 
                            @if(request('category'))
                                <small class="text-muted">(مصنفة)</small>
                            @endif
                        </h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <span class="text-muted me-3">
                            عرض {{ $blogs->firstItem() }} - {{ $blogs->lastItem() }} من {{ $blogs->total() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- قائمة المدونات -->
        @if($blogs->count() > 0)
            <div class="row">
                @foreach($blogs as $blog)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card blog-card h-100 shadow-sm">
                            <!-- صورة المدونة -->
                            @if($blog->image)
                                <img src="{{ asset( 'storage/' . $blog->image) }}"
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
                                    @if($blog->categories->count() > 2)
                                        <span class="badge bg-secondary">+{{ $blog->categories->count() - 2 }}</span>
                                    @endif
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
                                
                                <!-- وقت القراءة -->
                                <div class="read-time mb-3">
                                    <i class="far fa-clock me-1"></i>
                                    @php
                                        $wordCount = count(preg_split('/\s+/', $blog->content));
                                        $readTime = ceil($wordCount / 200);
                                    @endphp
                                    وقت القراءة: {{ $readTime }} دقيقة
                                </div>
                                
                                <!-- الإجراءات -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('blog.show', $blog) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-book-reader me-1"></i> اقرأ المزيد
                                    </a>
                                    
                                    <div class="d-flex align-items-center">
                                        <!-- المشاهدات -->
                                        <span class="text-muted me-3">
                                            <i class="far fa-eye me-1"></i> {{ $blog->views }}
                                        </span>
                                        
                                        <!-- زر المفضلة -->
                                            <a href="{{ route('favorites.toggle', $blog) }}" 
                                               class="favorite-toggle text-decoration-none"
                                               data-blog-id="{{ $blog->id }}">
                                                <i class="{{ $blog->isFavoritedBy(auth()->id()) ? 'fas' : 'far' }} fa-heart favorite-btn {{ $blog->isFavoritedBy(auth()->id()) ? 'active' : '' }}"></i>
                                            </a>
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
                                        <i class="fas fa-user-edit me-1"></i>
                                        {{ $blog->created_at->format('Y-m-d') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if ($blogs->hasPages())
    <div class="d-flex justify-content-center mt-5">
        <nav aria-label="تصفح الصفحات">
            <ul class="pagination pagination-lg">
                {{-- زر السابق --}}
                <li class="page-item {{ $blogs->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $blogs->previousPageUrl() }}" aria-label="السابق">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>

                {{-- الأرقام --}}
                @foreach ($blogs->getUrlRange(1, $blogs->lastPage()) as $page => $url)
                    @if ($page == $blogs->currentPage())
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                {{-- زر التالي --}}
                <li class="page-item {{ !$blogs->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $blogs->nextPageUrl() }}" aria-label="التالي">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
@endif
        @else
            <div class="text-center py-5">
                <div class="display-1 text-muted mb-4">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="text-muted mb-3">لا توجد نتائج</h3>
                <p class="text-muted mb-4">
                    @if(request('search'))
                        لم يتم العثور على مدونات تطابق بحثك "{{ request('search') }}"
                    @elseif(request('category'))
                        لا توجد مدونات في هذه الفئة حالياً
                    @else
                        لا توجد مدونات متاحة حالياً
                    @endif
                </p>
                <a href="{{ route('blog.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>عرض جميع المدونات
                </a>
            </div>
        @endif
    </div>
</div>
@endsection