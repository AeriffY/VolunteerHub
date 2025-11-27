@extends('layouts.app')

@section('title', '个人中心')

@section('content')

    <div class="mb-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

    <div class="container"> {{-- 增加一个容器以居中内容 --}}
        @php
            $isLoggedIn = Auth::check();
            $returnMessage='';
            $targetRoute = 'activities.index'; // 默认是普通用户主页
            if ($isLoggedIn) {
                $userRole = Auth::user()->role ?? ''; 
                if ($userRole === 'admin') { 
                    $targetRoute = 'admin.activities.index';
                    $returnMessage='返回活动管理';
                } else {
                    $targetRoute = 'activities.index';
                    $returnMessage='返回活动广场';
                }
            }
            $finalHref = $isLoggedIn ? route($targetRoute) : '#';
        @endphp
        <a href="{{ $finalHref }}" class="text-decoration-none d-inline-flex align-items-center mb-4 text-primary fw-bold fs-4 py-1">
            <i class="bi bi-arrow-left me-2"></i> {{$returnMessage}}
        </a>
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <h1><i class="bi bi-person-circle me-2 text-success"></i>个人中心</h1>
            <a href="{{ route('profile.exportPdf') }}" class="btn btn-outline-danger">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> 导出时长PDF
            </a>
        </div>
    
        <div class="row">
            {{-- 累计志愿服务时长卡片 --}}
            <div class="col-md-6 mb-4">
                {{-- 使用 bg-primary 样式，它会被 app.scss 中的 .card-header.bg-primary 覆盖为主题绿 --}}
                <div class="card h-100 shadow-sm border-0 activity-card">
                    <div class="card-header bg-primary text-white fw-bold">
                        <i class="bi bi-clock-history me-1"></i> 累计志愿服务时长
                    </div>
                    <div class="card-body text-center py-5">
                        <p class="display-3 fw-bolder text-success mb-0">
                            {{ number_format((float)($hours->total_hours ?? 0), 2) }}
                        </p>
                        <p class="fs-5 text-muted mb-0">小时</p>
                    </div>
                </div>
            </div>
            
            {{-- 我的勋章卡片 --}}
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0 activity-card">
                    <div class="card-header bg-primary text-white fw-bold">
                        <i class="bi bi-award me-1"></i> 我的勋章
                    </div>
                    <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                        
                        @php
                            $totalHours = (float)($hours->total_hours ?? 0);
                            $isExcellentVolunteer = $totalHours >= 10.0;
                        @endphp
    
                        @if ($isExcellentVolunteer)
                            <div class="py-3">
                                <img src="{{ asset('images/medal.png') }}" 
                                     alt="优秀志愿者勋章" 
                                     class="img-fluid mb-3 shadow-lg" 
                                     style="width: 100px; height: 100px; border: 4px solid #38c172; border-radius: 50%;">
                                <h4 class="fw-bolder text-success mb-1">🏅 优秀志愿者</h4>
                                <p class="text-muted small mb-0">已达成 10 小时服务标准！</p>
                            </div>
                        @else
                            <div class="text-center p-3">
                                <i class="bi bi-award-fill text-secondary opacity-50 mb-3" style="font-size: 4rem;"></i>
                                <h5 class="text-muted mb-2">解锁优秀志愿者勋章</h5>
                                <p class="text-secondary small mb-1">
                                    累计服务时长达到 10.00 小时可解锁此勋章。
                                </p>
                                <p class="fw-bold mb-0 text-primary">
                                    当前进度: {{ number_format($totalHours, 2) }} / 10.00 小时
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    
        <h3 class="mt-4 mb-3 border-bottom pb-2">
            <i class="bi bi-list-columns-reverse me-1 text-primary"></i> 我的活动记录
        </h3>
        
        <div class="list-group shadow-sm">
            {{-- 
                控制器应传入 $registrations (包含 activity 关联)
                查询 'registrations' 表中 'user_id' 为当前用户的记录
            --}}
            @forelse($registrations as $reg)
    @php
        $isRegistered = $reg->status == 'registered';
        $statusClass = $isRegistered ? 'success' : 'secondary';
        $statusText = $isRegistered ? '已报名' : '已取消';
        $iconClass = $isRegistered ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
        
        // 假设只有已完成/已签到的活动才允许上传回顾
        $canUploadReview = $reg->activity->status === 'completed'; 
    @endphp
    
    {{-- 注意：现在 list-group-item 不再是唯一的链接，而是包含内容的容器 --}}
    <div class="list-group-item py-3">
        <div class="d-flex w-100 justify-content-between align-items-start">
            <div>
                <a href="{{ route('activities.show', $reg->activity->id) }}" class="text-decoration-none">
                    <h5 class="mb-1 fw-bold text-dark">{{ $reg->activity->title }}</h5>
                </a>
                <p class="mb-1 text-muted small">
                    <i class="bi bi-calendar-event me-1"></i> 活动日期: 
                    <span class="fw-semibold text-dark">{{ $reg->activity->start_time->format('Y年m月d日') }}</span>
                </p>
            </div>
            
            <span class="badge bg-{{ $statusClass }} py-2 px-3 fw-normal flex-shrink-0 ms-3">
                <i class="bi {{ $iconClass }} me-1"></i> {{ $statusText }}
            </span>
        </div>
        
        {{-- 新增：按钮功能区域 --}}
        <div class="card-footer bg-light border-0 p-4 pt-0 mt-3">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <a href="{{ route('activities.show', $reg->activity->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i> 查看详情
                </a>

                @if($reg->activity->status === 'completed')
                    
                    @php
    // 获取当前用户对该活动的评论 (因为是一对多，但这里只取第一条，即他自己的那条)
    $myReview = $reg->activity->reviews->first();
@endphp
                    @if($myReview)
                        
                        <a href="{{ route('reviews.show', $myReview->id) }}" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-chat-square-quote-fill me-1"></i> 查看我的回顾
                        </a>

                    @else
                        <a href="{{ route('activities.review.create', $reg->activity->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-file-earmark-arrow-up-fill me-1"></i> 上传图文
                        </a>
                    @endif

                @else
                    <small class="text-muted ms-1 border-start ps-2">
                        @if($reg->activity->status === 'published')
                            <i class="bi bi-hourglass"></i> 等待活动开始
                        @elseif($reg->activity->status === 'in_progress')
                            <i class="bi bi-play-circle-fill text-success"></i> 活动进行中
                        @endif
                    </small>
                @endif
            </div>
</div>
    </div>
@empty
    <div class="alert alert-info mb-0 text-center">
        <i class="bi bi-info-circle me-1"></i> 您还没有报名任何活动。快去发现新活动吧！
    </div>
@endforelse
        </div>

    </div> {{-- /container --}}
@endsection
