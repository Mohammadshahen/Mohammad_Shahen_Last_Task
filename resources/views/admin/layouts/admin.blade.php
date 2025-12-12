<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم')</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: 250px;
            background: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .sidebar a.active {
            background: #007bff;
        }
        .main-content {
            margin-right: 250px;
            padding: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header text-center mb-4">
            <h4 class="text-white">لوحة التحكم</h4>
        </div>
        
        <ul class="list-unstyled">
            <li>
                {{-- <a href="{{ route('admin.dashboard') }}" 
                   class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i> الرئيسية
                </a> --}}
            </li>
            <li>
                <a href="{{ route('blogs.index') }}" 
                   class="{{ request()->routeIs('blogs.*') ? 'active' : '' }}">
                    <i class="fas fa-blog me-2"></i> المدونات
                </a>
            </li>
            <li>
                
                <a href="{{ url('blogs_trash') }}"class="{{ request()->routeIs('blogs_trash') ? 'active' : '' }}">
                    <i class="fas fa-trash"></i> عرض سلة المهملات
                    @php
                        $trashCount = App\Models\Blog::onlyTrashed()->count();
                    @endphp
                    @if($trashCount > 0)
                        <span class="badge bg-danger">{{ $trashCount }}</span>
                    @endif
                </a>
            
            </li>
            
            <li>
                <a href="{{ route('categories.index') }}" 
                class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags me-2"></i> الفئات
                </a>
            </li>
            <li>
                {{-- <a href="{{ route('admin.categories.index') }}" 
                   class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags me-2"></i> الفئات
                </a> --}}
            </li>
            <li>
                {{-- <a href="{{ route('home') }}" target="_blank">
                    <i class="fas fa-external-link-alt me-2"></i> الموقع الرئيسي
                </a> --}}
            </li>
            <li class="mt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <nav class="navbar navbar-light bg-white mb-4 rounded shadow-sm">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">
                    @yield('page-title', 'لوحة التحكم')
                </span>
                <div>
                    <span class="text-muted me-3">
                        <i class="fas fa-user me-1"></i> {{ Auth::user()->name}}
                    </span>
                </div>
            </div>
        </nav>

        <!-- Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Content -->
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        // تفعيل CKEditor لمحتوى المدونة
        @if(request()->routeIs('admin.blogs.create') || request()->routeIs('admin.blogs.edit'))
            CKEDITOR.replace('content', {
                language: 'ar',
                contentsLangDirection: 'rtl'
            });
        @endif
    </script>
    
    @yield('scripts')
</body>
</html>