@extends('layouts.app')

@section('title', '活动回顾 - ' . $review->review_title)

@section('content')
    <div class="container py-4">
        
        {{-- 返回按钮 --}}
        {{-- 返回到个人中心，或者您可以根据需要改为返回活动详情页 --}}
        <a href="{{ route('profile.show') }}" class="text-decoration-none d-inline-flex align-items-center mb-4 text-primary fw-bold fs-5">
            <i class="bi bi-arrow-left me-2"></i> 返回个人中心
        </a>

        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white p-4">
                {{-- 回顾标题 --}}
                <h1 class="fw-bold mb-0">
                    <i class="bi bi-file-earmark-text me-2"></i> {{ $review->title }}
                </h1>
                {{-- 关联活动名称 --}}
                <p class="mb-0 fs-5 mt-1">{{ $activity->title }}</p>
            </div>
            
            <div class="card-body p-md-5">
                
                {{-- 作者和发布时间信息 --}}
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <div>
                        <span class="text-muted me-3">
                            <i class="bi bi-person-fill me-1"></i> 作者: 
                            <span class="fw-semibold text-dark">{{ $review->user->name ?? '未知用户' }}</span>
                        </span>
                        <span class="text-muted">
                            <i class="bi bi-calendar-check me-1"></i> 发布于: 
                            <span class="fw-semibold text-dark">{{ $review->created_at->format('Y年m月d日 H:i') }}</span>
                        </span>
                    </div>
                </div>

                {{-- 1. 回顾内容 --}}
                <h3 class="mb-3 text-primary fw-bold">回顾详情</h3>
                <div class="review-content mb-5 fs-5" style="line-height: 1.8;">
                    {{-- 使用 nl2br 确保换行符被渲染为 <br> --}}
                    {!! nl2br(e($review->content)) !!}
                </div>
                
                {{-- 2. 活动照片 --}}
                
                @if(!empty($review->image_paths) && count($review->image_paths) > 0)
                    
                    <h3 class="mb-4 text-primary fw-bold">活动照片 ({{ count($review->image_paths) }} 张)</h3>
                    
                    <div class="row g-3">
                        @foreach ($review->image_paths as $path)
                            <div class="col-md-4 col-sm-6">
                                <a href="{{ asset('storage/' . $path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $path) }}" 
                                        class="img-fluid rounded-3 shadow-sm hover-grow" 
                                        alt="活动照片" 
                                        style="aspect-ratio: 16/9; object-fit: cover;">
                                </a>
                            </div>
                        @endforeach
                    </div>

                @else
                    <div class="alert alert-warning border-0 shadow-sm" role="alert">
                        <i class="bi bi-image me-2"></i> 作者没有上传活动照片。
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- 简单的 CSS 动画，您可以将其放在 app.scss 或 style 标签中 --}}
    <style>
        .hover-grow {
            transition: transform 0.3s ease;
        }
        .hover-grow:hover {
            transform: scale(1.03);
        }
        .review-content {
            white-space: pre-wrap; /* 确保保留多余的空格和换行符 */
        }
    </style>
@endsection