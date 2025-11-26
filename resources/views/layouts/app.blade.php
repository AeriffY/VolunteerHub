<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '志愿汇') }}</title>

    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@700&display=swap" rel="stylesheet">    
    
    @vite(['resources/sass/app.scss', 'resources/js/app.js','resources/js/bootstrap.js'])
</head>
<body>
    <div id="app">
        <!-- 修改导航栏样式：使用深色文字、主色背景和自定义类 -->
        <nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-lg volunteer-navbar">
            <div class="container">
                @php
                    $isLoggedIn = Auth::check();
                    
                    // 1. 确定跳转目标路由
                    $targetRoute = 'activities.index'; // 默认是普通用户主页
                    
                    if ($isLoggedIn) {
                        // 登录后，检查 role 字段是否为管理员
                        // 注意：如果 role 字段不存在，访问 Auth::user()->role 可能会导致错误。
                        // 我们需要使用 ?? '' 来确保安全访问。
                        $userRole = Auth::user()->role ?? ''; 
                        
                        // 假设管理员角色是 'admin'，请根据你上一步调试的结果来确认这个值
                        if ($userRole === 'admin') { 
                            $targetRoute = 'admin.activities.index';
                        } else {
                            $targetRoute = 'activities.index';
                        }
                    }
                    
                    // 2. 确定最终链接 (href) 的值
                    // 如果已登录，则使用 route() 生成链接；否则，使用 '#'
                    $finalHref = $isLoggedIn ? route($targetRoute) : '#';
                    
                @endphp
                
                <a class="navbar-brand" href="{{ $finalHref }}">
                    {{ config('app.name', '志愿汇') }}
                </a>
                <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button> -->

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        {{-- 可以在这里添加其他导航链接 --}}
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="bi bi-person-badge me-1"></i> 个人中心
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
