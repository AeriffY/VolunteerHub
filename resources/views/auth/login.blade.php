@extends('layouts.app')

@section('content')

<!-- {{-- START: 封面闪屏容器 (Splash Screen) - 极简主义设计 --}}
<div id="splash-screen">
    <div class="splash-center-content">
        {{-- 主标题 --}}
        <h1 class="splash-text">志愿汇</h1>
        
        {{-- 鼓励语 --}}
        <h2 class="splash-slogan">用爱心，点亮世界微光</h2>
    </div>
</div>
{{-- END: 封面闪屏容器 --}} -->

{{-- 登录主内容区域 - 初始设置为透明，等待闪屏结束后再显示 --}}
{{-- FIX: 在 container 上添加 mt-5 类，将其向下推 --}}
<div class="container mt-5" id="login-content" style="opacity: 0;">
    <div class="row justify-content-center">
        {{-- 调整容器宽度，让卡片更集中 --}}
        <div class="col-md-6 col-lg-5"> 
            {{-- 美化卡片：增加阴影和圆角 --}}
            <div class="card shadow-lg border-0 rounded-4"> 
                
                {{-- 美化卡片头部：使用 primary 颜色和白色文本 --}}
                <div class="card-header bg-primary text-white text-center rounded-top-4 py-3">
                    <h3 class="fw-bold mb-0">{{ __('Login') }}</h3>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- 邮箱输入框 --}}
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- 密码输入框 --}}
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- 记住我 --}}
                        <div class="row mb-3">
                            <div class="col-md-8 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- 登录按钮和忘记密码链接 --}}
                        <div class="row mb-0 mt-4 justify-content-center">
                            <div class="col-12 col-md-8">
                                {{-- 使用 btn-lg 和 shadow 让按钮更突出 --}}
                                <button type="submit" class="btn btn-primary w-100 btn-lg shadow">
                                    {{ __('Login') }}
                                </button>
                                
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link mt-2 w-100 text-decoration-none text-center d-block" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 

{{-- START: 闪屏 JavaScript - 放在 @endsection 之后，确保在内容渲染后执行 --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const splash = document.getElementById('splash-screen');
        const loginContent = document.getElementById('login-content');

        // 安全检查：确保关键元素存在
        if (!splash || !loginContent) {
            // 如果找不到元素，直接显示登录内容并退出
            if(loginContent) {
                 loginContent.style.opacity = '1';
            }
            return;
        }

        // 定义时间（毫秒）
        const displayDuration = 2000; // FIX 1: 将 3000 毫秒缩短为 2000 毫秒 (2 秒)
        const fadeDuration = 1500; // 1.5秒淡出时间保持不变 (与 app.scss transition 一致)

        // 1. 设置登录内容淡入过渡（让它显示时是渐变的）
        loginContent.style.transition = `opacity ${fadeDuration / 1000}s ease-in`;
        
        // 2. 3 秒后开始淡出闪屏
        setTimeout(() => {
            // 触发 CSS 淡出动画
            splash.style.opacity = '0'; 

            // 3. 等待淡出动画结束（1.5秒），然后彻底隐藏闪屏并显示登录内容
            setTimeout(() => {
                splash.style.display = 'none'; // 彻底隐藏闪屏
                loginContent.style.opacity = '1'; // 显示登录内容
            }, fadeDuration);

        }, displayDuration); 
    });
</script>
{{-- END: 闪屏 JavaScript --}}
