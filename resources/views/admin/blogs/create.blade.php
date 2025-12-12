@extends('admin.layouts.admin')

@section('title', 'إضافة مدونة جديدة')
@section('page-title', 'إضافة مدونة جديدة')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">إضافة مدونة جديدة</h5>
    </div>
    
    <div class="card-body">
        <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <!-- العنوان -->
                <div class="col-md-12 mb-3">
                    <label for="title" class="form-label">عنوان المدونة *</label>
                    <input type="text" 
                           class="form-control @error('title') is-invalid @enderror" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}"
                           placeholder="أدخل عنوان المدونة" 
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- المحتوى -->
                <div class="col-md-12 mb-3">
                    <label for="content" class="form-label">محتوى المدونة *</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" 
                              id="content" 
                              name="content" 
                              rows="10" 
                              placeholder="اكتب محتوى المدونة هنا..." 
                              required>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- الصورة -->
                <div class="col-md-6 mb-3">
                    <label for="image" class="form-label">صورة المدونة</label>
                    <input type="file" 
                           class="form-control @error('image') is-invalid @enderror" 
                           id="image" 
                           name="image"
                           accept="image/*">
                    <small class="text-muted">الصيغ المسموحة: JPEG, PNG, JPG, GIF | الحد الأقصى: 2MB</small>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    
                    <!-- معاينة الصورة -->
                    <div id="imagePreview" class="mt-2" style="display: none;">
                        <img id="previewImage" src="#" alt="معاينة الصورة" 
                             style="max-width: 200px; max-height: 150px;" class="img-thumbnail">
                    </div>
                </div>
                
                <!-- الفئات -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">الفئات *</label>
                    <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                        @forelse($categories as $category)
                            <div class="form-check mb-2">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="categories[]" 
                                       value="{{ $category->id }}" 
                                       id="category{{ $category->id }}"
                                       {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="category{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @empty
                            <p class="text-muted">لا توجد فئات متاحة. <a href="{{ route('admin.categories.create') }}">أضف فئات أولاً</a></p>
                        @endforelse
                    </div>
                    @error('categories')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    @error('categories.*')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <!-- الأزرار -->
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> حفظ المدونة
                </button>
                <a href="{{ route('blogs.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // معاينة الصورة قبل الرفع
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const image = document.getElementById('previewImage');
        
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                image.src = e.target.result;
                preview.style.display = 'block';
            }
            
            reader.readAsDataURL(this.files[0]);
        } else {
            preview.style.display = 'none';
        }
    });
</script>
@endsection