@extends('admin.layouts.admin')

@section('title', 'سلة المهملات')
@section('page-title', 'سلة المهملات')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">المدونات المحذوفة</h5>
        <a href="{{ route('blogs.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-right"></i> العودة للمدونات
        </a>
    </div>
    
    <div class="card-body">
        @if($blogs->count() > 0)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                المدونات الموجودة هنا تم حذفها مؤقتاً ويمكن استعادتها أو حذفها نهائياً.
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-warning">
                        <tr>
                            <th width="50">#</th>
                            <th>العنوان</th>
                            <th width="150">تاريخ الحذف</th>
                            <th width="120">مدة الحذف</th>
                            <th width="250">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($blogs as $blog)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ Str::limit($blog->title, 60) }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        تاريخ الإنشاء: {{ $blog->created_at->format('Y-m-d') }}
                                    </small>
                                </td>
                                <td>
                                    <small>
                                        {{ $blog->deleted_at->format('Y-m-d H:i') }}
                                        <br>
                                        <span class="text-muted">{{ $blog->deleted_at->diffForHumans() }}</span>
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $blog->deleted_at->diffInDays(now()) > 30 ? 'danger' : 'warning' }}">
                                        {{ $blog->deleted_at->diffForHumans(['parts' => 2]) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <form action="{{ route('blogs_restore', $blog->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    title="استعادة"
                                                    onclick="return confirm('هل تريد استعادة هذه المدونة؟')">
                                                <i class="fas fa-undo"></i> استعادة
                                            </button>
                                        </form>
                                        
                                        {{-- <a href="{{ route('blogs.show', $blog) }}" 
                                           class="btn btn-sm btn-info" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a> --}}
                                        
                                        <form action="{{ route('blogs_forceDelete', $blog->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    title="حذف نهائي"
                                                    onclick="return confirm('هل أنت متأكد من الحذف النهائي؟ هذه العملية لا يمكن التراجع عنها!')">
                                                <i class="fas fa-trash-alt"></i> حذف نهائي
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
            <div class="d-flex justify-content-center mt-4">
                {{ $blogs->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-trash-alt fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">سلة المهملات فارغة</h4>
                <p class="text-muted">لا توجد مدونات محذوفة</p>
                <a href="{{ route('blogs.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right"></i> العودة للمدونات
                </a>
            </div>
        @endif
    </div>
    
    <div class="card-footer">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="text-muted">
                    <i class="fas fa-info-circle me-2"></i>
                    
                </div>
            </div>
            
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    @if($blogs->count() > 0)
                        
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>




@endsection

@section('scripts')
<script>
    // تفعيل/تعطيل زر حذف الكل بناءً على تأكيد المستخدم
    document.getElementById('confirmDeleteAll').addEventListener('change', function() {
        document.getElementById('emptyTrashBtn').disabled = !this.checked;
    });

    // تأكيد الحذف النهائي
    function confirmForceDelete(event, blogTitle) {
        if (!confirm(`هل أنت متأكد من الحذف النهائي للمدونة: "${blogTitle}"؟\n\nهذه العملية لا يمكن التراجع عنها!`)) {
            event.preventDefault();
        }
    }

    // عرض مدة الحذف
    document.addEventListener('DOMContentLoaded', function() {
        const deletedItems = document.querySelectorAll('.deleted-item');
        deletedItems.forEach(item => {
            const deletedAt = new Date(item.dataset.deletedAt);
            const now = new Date();
            const diffDays = Math.floor((now - deletedAt) / (1000 * 60 * 60 * 24));
            
            if (diffDays > 25) {
                item.classList.add('table-danger');
            } else if (diffDays > 15) {
                item.classList.add('table-warning');
            }
        });
    });

    // Auto-refresh كل 60 ثانية
    setInterval(function() {
        if (window.location.pathname.includes('trash')) {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    // تحديث جدول المدونات فقط
                    const parser = new DOMParser();
                    const newDoc = parser.parseFromString(html, 'text/html');
                    const newTable = newDoc.querySelector('.table');
                    if (newTable) {
                        document.querySelector('.table').innerHTML = newTable.innerHTML;
                    }
                    
                    // تحديث العدادات
                    const counts = newDoc.querySelectorAll('.count-badge');
                    counts.forEach((badge, index) => {
                        const oldBadges = document.querySelectorAll('.count-badge');
                        if (oldBadges[index]) {
                            oldBadges[index].textContent = badge.textContent;
                        }
                    });
                })
                .catch(error => console.log('Auto-refresh error:', error));
        }
    }, 60000); // 60 ثانية
</script>

<style>
    .table-danger {
        background-color: rgba(220, 53, 69, 0.05) !important;
    }
    
    .table-warning {
        background-color: rgba(255, 193, 7, 0.05) !important;
    }
    
    .deleted-item:hover {
        background-color: rgba(0, 0, 0, 0.02) !important;
    }
    
    .modal-header {
        border-bottom: 2px solid #dee2e6;
    }
    
    .modal-footer {
        border-top: 2px solid #dee2e6;
    }
    
    .count-badge {
        font-size: 0.9em;
        padding: 0.35em 0.65em;
    }
</style>
@endsection
       