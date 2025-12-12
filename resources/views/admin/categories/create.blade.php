@extends('admin.layouts.admin')

@section('title', 'إضافة فئة جديدة')
@section('page-title', 'إضافة فئة جديدة')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">إضافة فئة جديدة</h5>
            </div>
            
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">اسم الفئة *</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               placeholder="أدخل اسم الفئة" 
                               required
                               autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            يجب أن يكون اسم الفئة فريداً ولا يتجاوز 255 حرفاً
                        </div>
                    </div>
                    
                    <!-- مثال للفئات الموجودة -->
                    @if(App\Models\Category::count() > 0)
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> الفئات الحالية:</h6>
                            <div class="mt-2">
                                @foreach(App\Models\Category::latest()->limit(5)->get() as $existingCategory)
                                    <span class="badge bg-secondary me-1 mb-1">{{ $existingCategory->name }}</span>
                                @endforeach
                                @if(App\Models\Category::count() > 5)
                                    <span class="text-muted">و {{ App\Models\Category::count() - 5 }} أكثر...</span>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> حفظ الفئة
                        </button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection