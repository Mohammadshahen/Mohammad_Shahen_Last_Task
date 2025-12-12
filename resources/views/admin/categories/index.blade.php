@extends('admin.layouts.admin')

@section('title', 'إدارة الفئات')
@section('page-title', 'الفئات')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">قائمة الفئات</h5>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة فئة جديدة
        </a>
    </div>
    
    <div class="card-body">
        
        
        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>اسم الفئة</th>
                            <th width="120">عدد المدونات</th>
                            <th width="150">تاريخ الإنشاء</th>
                            <th width="200">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $category->name }}</strong>
                                    @if($category->blogs_count > 0)
                                        <br>
                                        <small class="text-muted">
                                            مرتبطة بـ {{ $category->blogs_count }} مدونة
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $category->blogs_count > 0 ? 'success' : 'secondary' }}">
                                        {{ $category->blogs_count }}
                                    </span>
                                </td>
                                <td>
                                    <small>
                                        {{ $category->created_at->format('Y-m-d') }}
                                        <br>
                                        <span class="text-muted">{{ $category->created_at->diffForHumans() }}</span>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('categories.show', $category) }}" 
                                           class="btn btn-sm btn-info" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('categories.edit', $category) }}" 
                                           class="btn btn-sm btn-warning" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('categories.destroy', $category) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    title="حذف"
                                                    onclick="return confirm('هل أنت متأكد من حذف هذه الفئة؟')">
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
             @if ($categories->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    <nav aria-label="تصفح الصفحات">
                        <ul class="pagination pagination-lg">
                            {{-- زر السابق --}}
                            <li class="page-item {{ $categories->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $categories->previousPageUrl() }}" aria-label="السابق">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>

                            {{-- الأرقام --}}
                            @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                                @if ($page == $categories->currentPage())
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
                            <li class="page-item {{ !$categories->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $categories->nextPageUrl() }}" aria-label="التالي">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-tags fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">لا توجد فئات</h4>
                <p class="text-muted">ابدأ بإضافة فئة جديدة</p>
                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> إضافة فئة جديدة
                </a>
            </div>
        @endif
    </div>
    
    <div class="card-footer">
        <div class="row">
            <div class="col-md-6">
                <span class="text-muted">
                    إجمالي الفئات: <strong>{{ $categories->total() }}</strong>
                </span>
            </div>
            <div class="col-md-6 text-end">
                <span class="text-muted">
                    عدد المدونات المرتبطة: 
                    <strong>{{ App\Models\Category::withCount('blogs')->get()->sum('blogs_count') }}</strong>
                </span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // تأكيد الحذف مع التحذير إذا كانت الفئة مرتبطة بمدونات
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('form[action*="destroy"]');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const categoryName = this.closest('tr').querySelector('td:nth-child(2) strong').textContent;
                const blogCount = this.closest('tr').querySelector('.badge').textContent;
                
                if (parseInt(blogCount) > 0) {
                    e.preventDefault();
                    alert('⚠️ لا يمكن حذف الفئة "' + categoryName + '" لأنها مرتبطة بـ ' + blogCount + ' مدونة.');
                } else {
                    if (!confirm('هل أنت متأكد من حذف الفئة "' + categoryName + '"؟')) {
                        e.preventDefault();
                    }
                }
            });
        });
    });
</script>
@endsection