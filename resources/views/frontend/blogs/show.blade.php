@extends('frontend.layouts.app')

@section('title', $blog->title)

@section('hero')
<section class="hero-section" style="background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <!-- الفئات -->
                <div class="mb-3">
                    @foreach($blog->categories as $category)
                        <a href="{{ route('blog.index', ['category' => $category->id]) }}" 
                           class="badge bg-light text-dark text-decoration-none me-2 mb-2">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
                
                <!-- العنوان -->
                <h1 class="display-5 fw-bold mb-3">{{ $blog->title }}</h1>
                
                <!-- معلومات المدونة -->
                <div class="d-flex flex-wrap align-items-center text-white mb-4">
                    <div class="me-4 mb-2">
                        <i class="far fa-calendar me-1"></i>
                        {{ $blog->created_at->format('Y-m-d') }}
                    </div>
                    <div class="me-4 mb-2">
                        <i class="far fa-clock me-1"></i>
                        @php
                            $wordCount = count(preg_split('/\s+/', $blog->content));
                            $readTime = ceil($wordCount / 200);
                        @endphp
                        {{ $readTime }} دقيقة قراءة
                    </div>
                    <div class="me-4 mb-2">
                        <i class="far fa-eye me-1"></i>
                        {{ $blog->views }} مشاهدة
                    </div>
                    
                    @auth
                        <div class="mb-2">
                            <a href="{{ route('favorites.toggle', $blog) }}" 
                               class="favorite-toggle text-white text-decoration-none"
                               data-blog-id="{{ $blog->id }}">
                                <i class="{{ $blog->isFavoritedBy(auth()->id()) ? 'fas' : 'far' }} fa-heart me-1"></i>
                                {{ $blog->isFavoritedBy(auth()->id()) ? 'في المفضلة' : 'أضف للمفضلة' }}
                            </a>
                        </div>
                    @endauth
                </div>
                
                <!-- زر المشاركة -->
                <div class="share-buttons">
                    <button class="btn btn-light btn-sm me-2 mb-2" onclick="shareOnFacebook()">
                        <i class="fab fa-facebook me-1"></i> مشاركة
                    </button>
                    <button class="btn btn-light btn-sm me-2 mb-2" onclick="shareOnTwitter()">
                        <i class="fab fa-twitter me-1"></i> تغريدة
                    </button>
                    <button class="btn btn-light btn-sm mb-2" onclick="copyLink()">
                        <i class="fas fa-link me-1"></i> نسخ الرابط
                    </button>
                </div>
            </div>
            
            <!-- صورة المدونة -->
            @if($blog->image)
                <div class="col-lg-4">
                    <div class="text-center">
                        <img src="{{ asset('storage/' . $blog->image) }}" 
                             alt="{{ $blog->title }}" 
                             class="img-fluid rounded shadow-lg"
                             style="max-height: 300px;">
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="row">
    <!-- محتوى المدونة -->
    <div class="col-lg-8 mb-5">
        <div class="card shadow-sm">
            <div class="card-body blog-content">
                <!-- محتوى المدونة -->
                <article>
                    {!! $blog->content !!}
                </article>
                
                <!-- تاجات -->
                <div class="mt-5 pt-4 border-top">
                    <h6 class="mb-3"><i class="fas fa-tags me-2"></i>كلمات مفتاحية</h6>
                    @foreach($blog->categories as $category)
                        <a href="{{ route('blog.index', ['category' => $category->id]) }}" 
                           class="badge bg-secondary text-decoration-none me-2 mb-2">
                            #{{ $category->name }}
                        </a>
                    @endforeach
                </div>
                
                <!-- أزرار التفاعل -->
                <div class="mt-4 d-flex justify-content-between">
                    @auth
                        <div>
                            <a href="{{ route('favorites.toggle', $blog) }}" 
                               class="favorite-toggle btn btn-outline-danger"
                               data-blog-id="{{ $blog->id }}">
                                <i class="{{ $blog->isFavoritedBy(auth()->id()) ? 'fas' : 'far' }} fa-heart me-1"></i>
                                {{ $blog->isFavoritedBy(auth()->id()) ? 'إزالة من المفضلة' : 'إضافة للمفضلة' }}
                            </a>
                        </div>
                    @else
                        <div>
                            <a href="{{ route('login') }}" class="btn btn-outline-danger">
                                <i class="far fa-heart me-1"></i> سجل الدخول لإضافة للمفضلة
                            </a>
                        </div>
                    @endauth
                    
                    <div class="share-buttons">
                        <button class="btn btn-outline-primary btn-sm me-2" onclick="shareOnFacebook()">
                            <i class="fab fa-facebook me-1"></i>
                        </button>
                        <button class="btn btn-outline-info btn-sm me-2" onclick="shareOnTwitter()">
                            <i class="fab fa-twitter me-1"></i>
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="copyLink()">
                            <i class="fas fa-link me-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- مدونات مشابهة -->
        @if($relatedBlogs->count() > 0)
            <div class="card shadow-sm mt-5">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-th-large me-2"></i>مدونات مشابهة</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($relatedBlogs as $related)
                            <div class="col-md-6 mb-3">
                                <div class="card border h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <a href="{{ route('blog.show', $related) }}" class="text-decoration-none">
                                                {{ Str::limit($related->title, 50) }}
                                            </a>
                                        </h6>
                                        <div class="small text-muted mb-2">
                                            @foreach($related->categories->take(2) as $cat)
                                                <span class="badge bg-secondary me-1">{{ $cat->name }}</span>
                                            @endforeach
                                        </div>
                                        <div class="small text-muted">
                                            <i class="far fa-calendar me-1"></i>
                                            {{ $related->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- إحصائيات المدونة -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>إحصائيات المدونة</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-3 d-flex justify-content-between">
                        <span><i class="fas fa-eye text-primary me-2"></i>عدد المشاهدات</span>
                        <strong class="text-primary">{{ $blog->views }}</strong>
                    </li>
                    <li class="mb-3 d-flex justify-content-between">
                        <span><i class="fas fa-tags text-success me-2"></i>عدد الفئات</span>
                        <strong class="text-success">{{ $blog->categories->count() }}</strong>
                    </li>
                    <li class="mb-3 d-flex justify-content-between">
                        <span><i class="fas fa-file-alt text-warning me-2"></i>عدد الكلمات</span>
                        <strong class="text-warning">{{count(preg_split('/\s+/', $blog->content))}}</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span><i class="fas fa-clock text-danger me-2"></i>وقت القراءة</span>
                        <strong class="text-danger">{{ ceil(count(preg_split('/\s+/', $blog->content)) / 200) }} دقيقة</strong>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- المدونات الأكثر مشاهدة -->
        @if($popularBlogs->count() > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-fire me-2"></i>الأكثر مشاهدة</h5>
                </div>
                <div class="card-body">
                    @foreach($popularBlogs as $popBlog)
                        <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <h6 class="mb-1">
                                <a href="{{ route('blog.show', $popBlog) }}" class="text-decoration-none">
                                    {{ Str::limit($popBlog->title, 40) }}
                                </a>
                            </h6>
                            <small class="text-muted d-block mb-1">
                                <i class="far fa-eye me-1"></i> {{ $popBlog->views }} مشاهدة
                            </small>
                            <small class="text-muted">
                                <i class="far fa-calendar me-1"></i> {{ $popBlog->created_at->diffForHumans() }}
                            </small>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- الفئات -->
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>تصفح الفئات</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach(App\Models\Category::withCount('blogs')->limit(6)->get() as $category)
                        <div class="col-6 mb-2">
                            <a href="{{ route('blog.index', ['category' => $category->id]) }}" 
                               class="btn btn-outline-success w-100 text-start">
                                <i class="fas fa-folder me-1"></i> {{ $category->name }}
                                <span class="badge bg-success float-end">{{ $category->blogs_count }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // مشاركة على فيسبوك
    function shareOnFacebook() {
        const url = encodeURIComponent(window.location.href);
        const title = encodeURIComponent('{{ $blog->title }}');
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${title}`, '_blank');
    }
    
    // مشاركة على تويتر
    function shareOnTwitter() {
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent('{{ $blog->title }}');
        window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
    }
    
    // نسخ الرابط
    function copyLink() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            alert('تم نسخ الرابط إلى الحافظة');
        });
    }
    
    // تنفيذ تابعية عند التمرير
    document.addEventListener('DOMContentLoaded', function() {
        const content = document.querySelector('.blog-content');
        const paragraphs = content.querySelectorAll('p');
        
        // إضافة تأثير ظهور التدريجي للفقرات
        paragraphs.forEach((p, index) => {
            p.style.opacity = '0';
            p.style.transform = 'translateY(20px)';
            p.style.transition = 'opacity 0.5s, transform 0.5s';
            
            setTimeout(() => {
                p.style.opacity = '1';
                p.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endsection