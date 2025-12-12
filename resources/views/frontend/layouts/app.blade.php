<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'مدونتي')</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
            padding-top: 56px;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .blog-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .blog-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .blog-card-img {
            height: 200px;
            object-fit: cover;
        }
        
        .favorite-btn {
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .favorite-btn.active {
            color: #ff4757;
            transform: scale(1.1);
        }
        
        .category-badge {
            font-size: 0.75rem;
            padding: 5px 10px;
            border-radius: 20px;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 80px 0;
            margin-bottom: 50px;
            border-radius: 0 0 20px 20px;
        }
        
        .footer {
            background: #343a40;
            color: white;
            padding: 40px 0;
            margin-top: 50px;
        }
        
        .read-time {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .blog-content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
        <div class="container">
            {{-- <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-blog me-2"></i>مدونتي
            </a> --}}
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        {{-- <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i> الرئيسية
                        </a> --}}
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('blogs.*') ? 'active' : '' }}" href="{{ route('blog.index') }}">
                            <i class="fas fa-newspaper me-1"></i> المدونات
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-tags me-1"></i> الفئات
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('blog.index') }}">جميع الفئات</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @foreach(App\Models\Category::take(5)->get() as $category)
                                <li>
                                    <a class="dropdown-item" href="{{ route('blog.index', ['category' => $category->id]) }}">
                                        {{ $category->name }}
                                        <span class="badge bg-primary float-end">{{ $category->blogs_count }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('favorites.*') ? 'active' : '' }}" 
                               href="{{ route('favorites.index') }}">
                                <i class="fas fa-heart me-1"></i> المفضلة
                                @php
                                    $favoriteCount = auth()->user()->favoriteBlogs()->count();
                                @endphp
                                @if($favoriteCount > 0)
                                    <span class="badge bg-danger">{{ $favoriteCount }}</span>
                                @endif
                            </a>
                        </li>
                        
                        @if(auth()->user()->is_admin)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('blogs.index') }}" target="_blank">
                                    <i class="fas fa-cog me-1"></i> لوحة التحكم
                                </a>
                            </li>
                        @endif
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> تسجيل الدخول
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i> تسجيل حساب
                            </a>
                        </li>
                    @endauth
                </ul>
                
                <!-- Search Form -->
                <form class="d-flex ms-3" action="{{ route('blog.index') }}" method="GET">
                    <input class="form-control me-2" type="search" name="search" 
                           placeholder="ابحث في المدونات..." value="{{ request('search') }}">
                    <button class="btn btn-outline-light" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @if(session('success'))
            <div class="container mt-4">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="container mt-4">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        
        @yield('hero')
        
        <div class="container py-4">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-blog me-2"></i>مدونتي</h5>
                    <p>منصة عربية متكاملة للمدونات والمقالات الثقافية والعلمية.</p>
                    <div class="social-icons mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h5>الفئات</h5>
                    <ul class="list-unstyled">
                        @foreach(App\Models\Category::take(5)->get() as $category)
                            <li>
                                <a href="{{ route('blog.index', ['category' => $category->id]) }}" 
                                   class="text-white-50 text-decoration-none">
                                    <i class="fas fa-angle-left me-1"></i> {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h5>روابط مهمة</h5>
                    <ul class="list-unstyled">
                        <li>
                            {{-- <a href="{{ route('home') }}" class="text-white-50 text-decoration-none">
                                <i class="fas fa-angle-left me-1"></i> الرئيسية
                            </a> --}}
                        </li>
                        <li>
                            <a href="{{ route('blog.index') }}" class="text-white-50 text-decoration-none">
                                <i class="fas fa-angle-left me-1"></i> جميع المدونات
                            </a>
                        </li>
                        @auth
                            <li>
                                <a href="{{ route('favorites.index') }}" class="text-white-50 text-decoration-none">
                                    <i class="fas fa-angle-left me-1"></i> المفضلة
                                </a>
                            </li>
                        @endauth
                        <li>
                            <a href="{{ route('login') }}" class="text-white-50 text-decoration-none">
                                <i class="fas fa-angle-left me-1"></i> تسجيل الدخول
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="bg-white">
            
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">© 2024 مدونتي. جميع الحقوق محفوظة.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-0">
                        تم إنشاء الموقع باستخدام 
                        <i class="fas fa-heart text-danger mx-1"></i> 
                        Laravel
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // AJAX for favorite toggle
        $(document).ready(function() {
            $('.favorite-toggle').on('click', function(e) {
                e.preventDefault();
                const btn = $(this);
                const url = btn.attr('href');
                const blogId = btn.data('blog-id');
                
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'POST'
                    },
                    beforeSend: function() {
                        btn.prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.success) {
                            // تغيير الأيقونة
                            const icon = btn.find('i');
                            if (response.is_favorited) {
                                icon.removeClass('far').addClass('fas');
                                btn.addClass('active');
                            } else {
                                icon.removeClass('fas').addClass('far');
                                btn.removeClass('active');
                            }
                            
                            // تحديث العداد في الشريط
                            $('.favorite-count').text(response.favorites_count);
                            
                            // إظهار الرسالة
                            showToast(response.message, 'success');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            showToast('يجب تسجيل الدخول أولاً', 'error');
                        } else {
                            showToast('حدث خطأ، حاول مرة أخرى', 'error');
                        }
                    },
                    complete: function() {
                        btn.prop('disabled', false);
                    }
                });
            });
            
            function showToast(message, type) {
                const toast = $(`
                    <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0 position-fixed bottom-0 end-0 m-3" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle me-2"></i>
                                ${message}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                `);
                
                $('body').append(toast);
                const bsToast = new bootstrap.Toast(toast[0]);
                bsToast.show();
                
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>