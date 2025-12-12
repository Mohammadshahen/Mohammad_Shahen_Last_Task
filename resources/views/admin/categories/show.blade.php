@extends('admin.layouts.admin')

@section('title', 'تفاصيل الفئة')
@section('page-title', 'تفاصيل الفئة: ' . $category->name)

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">معلومات الفئة</h5>
            </div>
            <div class="card-body">
                <!-- معلومات الفئة -->
                <div class="text-center mb-4">
                    <div class="display-1 text-primary mb-3">
                        <i class="fas fa-tag"></i>
                    </div>
                    <h3>{{ $category->name }}</h3>
                </div>
                
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <strong><i class="fas fa-hashtag me-2"></i> المعرف:</strong>
                        <span class="float-end">#{{ $category->id }}</span>
                    </li>
                    <li class="mb-3">
                        <strong><i class="fas fa-calendar-plus me-2"></i> تاريخ الإنشاء:</strong>
                        <span class="float-end">{{ $category->created_at->format('Y-m-d H:i') }}</span>
                    </li>
                    <li class="mb-3">
                        <strong><i class="fas fa-calendar-edit me-2"></i> آخر تعديل:</strong>
                        <span class="float-end">{{ $category->updated_at->format('Y-m-d H:i') }}</span>
                    </li>
                    <li class="mb-3">
                        <strong><i class="fas fa-blog me-2"></i> عدد المدونات:</strong>
                        <span class="float-end badge bg-{{ $category->blogs->count() > 0 ? 'success' : 'secondary' }}">
                            {{ $category->blogs->count() }}
                        </span>
                    </li>
                </ul>
                
                <!-- إجراءات -->
                <div class="mt-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> تعديل الفئة
                        </a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('هل أنت متأكد من حذف هذه الفئة؟')">
                                <i class="fas fa-trash"></i> حذف الفئة
                            </button>
                        </form>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> رجوع للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- إحصائيات -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-bar"></i> إحصائيات</h6>
            </div>
            <div class="card-body">
                @php
                    $totalBlogs = App\Models\Blog::count();
                    $categoryBlogs = $category->blogs->count();
                    $percentage = $totalBlogs > 0 ? round(($categoryBlogs / $totalBlogs) * 100, 1) : 0;
                @endphp
                
                <div class="progress mb-3" style="height: 20px;">
                    <div class="progress-bar bg-success" 
                         role="progressbar" 
                         style="width: {{ $percentage }}%"
                         aria-valuenow="{{ $percentage }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        {{ $percentage }}%
                    </div>
                </div>
                
                <div class="text-center">
                    <small class="text-muted">
                        هذه الفئة تمثل {{ $percentage }}% من إجمالي المدونات
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- المدونات المرتبطة -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">المدونات المرتبطة</h5>
                <span class="badge bg-primary">{{ $category->blogs->count() }} مدونة</span>
            </div>
            
            <div class="card-body">
                @if($category->blogs->count() > 0)
                    <div class="row">
                        @foreach($category->blogs as $blog)
                            <div class="col-md-6 mb-3">
                                <div class="card border">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <a href="{{ route('blogs.show', $blog) }}" 
                                               class="text-decoration-none">
                                                {{ Str::limit($blog->title, 40) }}
                                            </a>
                                        </h6>
                                        
                                        <!-- فئات المدونة -->
                                        <div class="mb-2">
                                            @foreach($blog->categories->where('id', '!=', $category->id)->take(2) as $otherCategory)
                                                <span class="badge bg-secondary me-1">{{ $otherCategory->name }}</span>
                                            @endforeach
                                            @if($blog->categories->count() > 3)
                                                <span class="text-muted">+{{ $blog->categories->count() - 3 }} أكثر</span>
                                            @endif
                                        </div>
                                        
                                        <!-- معلومات المدونة -->
                                        <div class="small text-muted">
                                            <i class="far fa-calendar me-1"></i>
                                            {{ $blog->created_at->format('Y-m-d') }}
                                            
                                            @if($blog->image)
                                                <span class="float-end">
                                                    <i class="fas fa-image text-info"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-blog fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد مدونات مرتبطة</h5>
                        <p class="text-muted">لم يتم ربط أي مدونة بهذه الفئة بعد</p>
                        <a href="{{ route('blogs.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إنشاء مدونة جديدة
                        </a>
                    </div>
                @endif
            </div>
            
            @if($category->blogs->count() > 0)
                <div class="card-footer">
                    <div class="text-center">
                        <small class="text-muted">
                            عرض {{ $category->blogs->count() }} من {{ $category->blogs->count() }} مدونة
                        </small>
                    </div>
                </div>
            @endif
        </div>
        
        
    </div>
</div>
@endsection