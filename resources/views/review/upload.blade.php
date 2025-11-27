@extends('layouts.app')

@section('title', '上传活动回顾 - ' . $activity->title)

@section('content')
    <div class="container py-4">
        
        {{-- 返回按钮 --}}
        <a href="{{ route('profile.show') }}" class="text-decoration-none d-inline-flex align-items-center mb-4 text-primary fw-bold fs-5">
            <i class="bi bi-arrow-left me-2"></i> 返回个人中心
        </a>

        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white p-4">
                <h1 class="fw-bold mb-0">
                    <i class="bi bi-file-earmark-text me-2"></i> 上传活动回顾
                </h1>
                <p class="mb-0 fs-5 mt-1">{{ $activity->title }}</p>
            </div>
            
            <div class="card-body p-md-5">
                
                {{-- 提示信息 --}}
                <div class="alert alert-info border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-lightbulb-fill me-2"></i>
                    请用心填写活动回顾，这将帮助其他志愿者了解活动的精彩瞬间！
                </div>

                {{-- 表单开始 --}}
                {{-- 注意：你需要定义 'activities.review.store' 路由和 ReviewController 来处理此表单 --}}
                <form action="{{ route('activities.review.store', $activity->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- 1. 回顾标题 --}}
                    <div class="mb-4">
                        <label for="review_title" class="form-label fw-bold">回顾标题 <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control form-control-lg @error('review_title') is-invalid @enderror" 
                               id="review_title" 
                               name="review_title" 
                               value="{{ old('review_title') }}" 
                               placeholder="请输入回顾标题，例如：一次难忘的校园清洁活动"
                               required>
                        @error('review_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 2. 回顾内容 (支持多行文本) --}}
                    <div class="mb-4">
                        <label for="content" class="form-label fw-bold">回顾内容 <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" 
                                  name="content" 
                                  rows="10" 
                                  placeholder="详细描述您在活动中的经历、感受和主要成果..." 
                                  required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 3. 上传图片 (允许多张) --}}
                    <div class="mb-5">
                        <label for="images" class="form-label fw-bold">活动照片 (可选，最多5张)</label>
                        <input type="file" 
                               class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" 
                               id="images" 
                               name="images[]" 
                               accept="image/*" 
                               multiple>
                        <div class="form-text">支持上传 JPG, PNG, GIF 等格式的图片。</div>
                        @error('images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="text-danger small mt-1">图片上传错误: {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 提交按钮 --}}
                    <button type="submit" class="btn btn-success btn-lg w-100 shadow-sm">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> 提交活动回顾
                    </button>
                    
                </form>
                {{-- 表单结束 --}}

            </div>
        </div>
    </div>
@endsection