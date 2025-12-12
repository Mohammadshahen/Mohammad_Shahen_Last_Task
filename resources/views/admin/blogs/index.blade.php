@extends('admin.layouts.admin')

@section('title', 'إدارة المدونات')
@section('page-title', 'المدونات')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">قائمة المدونات</h5>
        <a href="{{ route('blogs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة مدونة جديدة
        </a>
    </div>
    
    <div class="card-body">
        @if($blogs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th width="100">الصورة</th>
                            <th>العنوان</th>
                            <th>الفئات</th>
                            <th width="150">تاريخ الإنشاء</th>
                            <th width="200">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($blogs as $blog)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($blog->image)
                                    
                                        <img src="{{ asset('storage/' . $blog->image) }}" 
                                             alt="{{ $blog->title }}" 
                                             class="img-thumbnail"
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-image fa-2x"></i>
                                            <br>
                                            <small>بدون صورة</small>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ Str::limit($blog->title, 60) }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ Str::limit(strip_tags($blog->content), 80) }}
                                    </small>
                                </td>
                                <td>
                                    @forelse($blog->categories as $category)
                                        <span class="badge bg-info me-1 mb-1">{{ $category->name }}</span>
                                    @empty
                                        <span class="text-muted">بدون فئات</span>
                                    @endforelse
                                </td>
                                <td>
                                    <small>
                                        {{ $blog->created_at->format('Y-m-d') }}
                                        <br>
                                        <span class="text-muted">{{ $blog->created_at->diffForHumans() }}</span>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('blogs.show', $blog) }}" 
                                           class="btn btn-sm btn-info" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('blogs.edit', $blog) }}" 
                                           class="btn btn-sm btn-warning" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        {{-- <a href="{{ route('blogs.show', $blog) }}" 
                                           target="_blank"
                                           class="btn btn-sm btn-secondary" title="معاينة">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a> --}}
                                        <form action="{{ route('blogs.destroy', $blog) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    title="حذف"
                                                    onclick="return confirm('هل أنت متأكد من حذف هذه المدونة؟')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                <i class="fas fa-blog fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">لا توجد مدونات</h4>
                <p class="text-muted">ابدأ بإضافة مدونة جديدة</p>
                <a href="{{ route('blogs.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> إضافة مدونة جديدة
                </a>
            </div>
        @endif
    </div>
    
    <div class="card-footer">
        <div class="row">
            <div class="col-md-6">
                <span class="text-muted">
                    إجمالي المدونات: <strong>{{ $blogs->total() }}</strong>
                </span>
            </div>
            
        </div>
    </div>
</div>
@endsection