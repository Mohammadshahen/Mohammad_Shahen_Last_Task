@extends('admin.layouts.admin')

@section('title', 'تعديل فئة')
@section('page-title', 'تعديل فئة: ' . $category->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">تعديل فئة: {{ $category->name }}</h5>
            </div>
            
            <div class="card-body">
                <!-- معلومات سريعة -->
                <div class="alert alert-light mb-4">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">تاريخ الإنشاء:</small>
                            <br>
                            <strong>{{ $category->created_at->format('Y-m-d') }}</strong>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted">المدونات المرتبطة:</small>
                            <br>
                            <span class="badge bg-{{ $category->blogs()->count() > 0 ? 'success' : 'secondary' }}">
                                {{ $category->blogs()->count() }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">اسم الفئة *</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $category->name) }}"
                               placeholder="أدخل اسم الفئة" 
                               autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- المدونات المرتبطة -->
                    @if($category->blogs()->count() > 0)
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle"></i> تنبيه</h6>
                            <p class="mb-2">
                                هذه الفئة مرتبطة بـ <strong>{{ $category->blogs()->count() }}</strong> مدونة.
                                تغيير الاسم سيؤثر على جميع المدونات المرتبطة به.
                            </p>
                            <small class="text-muted">
                                آخر 3 مدونات مرتبطة:
                                @foreach($category->blogs()->latest()->limit(3)->get() as $blog)
                                    <br>• {{ $blog->title }}
                                @endforeach
                            </small>
                        </div>
                    @endif
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> حفظ التعديلات
                        </button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                        <a href="{{ route('categories.show', $category) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> معاينة
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection