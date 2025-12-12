@extends('admin.layouts.admin')

@section('title', 'تفاصيل المدونة')
@section('page-title', 'تفاصيل المدونة')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">تفاصيل المدونة</h5>
                <div>
                    <span class="badge bg-{{ $blog->trashed() ? 'danger' : 'success' }}">
                        {{ $blog->trashed() ? 'محذوفة' : 'نشطة' }}
                    </span>
                </div>
            </div>
            
            <div class="card-body">
                <!-- الصورة -->
                @if($blog->image)
                    <div class="text-center mb-4">
                        <img src="{{ asset('storage/' . $blog->image) }}" 
                             alt="{{ $blog->title }}" 
                             class="img-fluid rounded"
                             style="max-height: 400px;">
                    </div>
                @endif
                
                <!-- العنوان -->
                <h2 class="mb-3">{{ $blog->title }}</h2>
                
                <!-- المحتوى -->
                <div class="mb-4">
                    {!! $blog->content !!}
                </div>
                
                <!-- الفئات -->
                <div class="mb-4">
                    <h6><i class="fas fa-tags"></i> الفئات:</h6>
                    <div>
                        @forelse($blog->categories as $category)
                            <span class="badge bg-primary me-1 mb-1">{{ $category->name }}</span>
                        @empty
                            <span class="text-muted">لا توجد فئات</span>
                        @endforelse
                    </div>
                </div>
                
                <!-- المعلومات -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6><i class="fas fa-calendar-plus"></i> معلومات الإنشاء</h6>
                                <ul class="list-unstyled">
                                    <li><strong>تاريخ الإنشاء:</strong> {{ $blog->created_at->format('Y-m-d H:i') }}</li>
                                    <li><strong>آخر تعديل:</strong> {{ $blog->updated_at->format('Y-m-d H:i') }}</li>
                                    @if($blog->trashed())
                                        <li><strong>تاريخ الحذف:</strong> {{ $blog->deleted_at->format('Y-m-d H:i') }}</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6><i class="fas fa-chart-bar"></i> الإحصائيات</h6>
                                <ul class="list-unstyled">
                                    <li><strong>عدد الفئات:</strong> {{ $blog->categories->count() }}</li>
                                    <li><strong>عدد المحارف:</strong> {{ Str::length(strip_tags($blog->content)) }}</li>
                                    <li><strong>عدد الكلمات:</strong> {{ count(preg_split('/\s+/', $blog->content)) }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="{{ route('blogs.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> رجوع للقائمة
                        </a>
                    </div>
                    
                    <div class="btn-group">
                        @if(!$blog->trashed())
                            <a href="{{ route('blogs.edit' , $blog->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> تعديل
                            </a>
                            {{-- <a href="{{ route('blogs.show', $blog->id) }}" target="_blank" class="btn btn-info">
                                <i class="fas fa-external-link-alt"></i> معاينة
                            </a> --}}
                            <form action="{{ route('blogs.destroy', $blog) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('هل أنت متأكد من حذف هذه المدونة؟')">
                                    <i class="fas fa-trash"></i> حذف
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin_blogs.restore', $blog->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-undo"></i> استعادة
                                </button>
                            </form>
                            <form action="{{ route('admin_blogs.forceDelete', $blog->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('هل أنت متأكد من الحذف النهائي؟ هذه العملية لا يمكن التراجع عنها!')">
                                    <i class="fas fa-trash-alt"></i> حذف نهائي
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- إحصائيات سريعة -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-pie"></i> إحصائيات سريعة</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-blog text-primary me-2"></i>
                        <strong>المدونات النشطة:</strong> 
                        <span class="badge bg-primary float-end">
                            {{ App\Models\Blog::count() }}
                        </span>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-trash text-danger me-2"></i>
                        <strong>المدونات المحذوفة:</strong> 
                        <span class="badge bg-danger float-end">
                            {{ App\Models\Blog::onlyTrashed()->count() }}
                        </span>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-tags text-success me-2"></i>
                        <strong>الفئات:</strong> 
                        <span class="badge bg-success float-end">
                            {{ App\Models\Category::count() }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        
        
    </div>
</div>
@endsection