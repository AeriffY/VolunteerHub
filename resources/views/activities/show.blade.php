@extends('layouts.app')

@section('title', $activity->title)

@section('content')
    <div class="mb-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-x-circle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
    {{-- 活动标题和状态 --}}
    <div class="d-flex justify-content-between align-items-start mb-4 border-bottom pb-2">
        <h1 class="fw-bold text-dark">{{ $activity->title }}</h1>
        @php
            // 状态标签沿用 index.blade.php 的精简配色逻辑
            $status = $activity->status ?? 'draft';
            $statusMap = [
                'published' => ['bg-success', '报名中'],
                'in_progress' => ['bg-info', '进行中'],
                'completed' => ['bg-secondary', '已结束'],
                'cancelled' => ['bg-danger', '已取消'],
                'draft' => ['bg-warning', '待发布'],
            ];
            $badgeClass = $statusMap[$status][0] ?? 'bg-secondary';
            $badgeText = $statusMap[$status][1] ?? '未知';
        @endphp
        <span class="badge {{ $badgeClass }} fs-5 fw-bold py-2 px-3 shadow-sm">{{ $badgeText }}</span>
    </div>
    
    @php
        // 检查用户是否为非管理员，用于决定左右布局
        // 我们假设 Auth::user()->role === 'admin' 表示管理员
        $isNotAdmin = Auth::check() && (Auth::user()->role !== 'admin');
        
        // 如果是非管理员，显示右侧卡片，左侧占 col-md-8
        // 如果是管理员或未登录，不显示右侧卡片，左侧占 col-md-12
        $leftColClass = $isNotAdmin ? 'col-md-8' : 'col-md-12';
    @endphp

    <div class="row g-5">
        {{-- 左侧：活动描述和详情，根据是否是管理员调整宽度 --}}
        <div class="{{ $leftColClass }}">
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-body p-4">
                    <h4 class="pb-2 mb-3 border-bottom text-primary fw-semibold"><i class="bi bi-file-earmark-text me-2"></i> 活动描述</h4>
                    <p class="lead">{{ $activity->description }}</p>
                </div>
            </div>
            
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <h4 class="pb-2 mb-3 border-bottom text-primary fw-semibold"><i class="bi bi-info-circle me-2"></i> 活动详情</h4>
                    <ul class="list-unstyled detail-list">
                        <li class="mb-2"><i class="bi bi-calendar-range me-2 text-primary"></i> <strong>开始时间:</strong> {{ $activity->start_time->format('Y年m月d日 H:i') }}</li>
                        <li class="mb-2"><i class="bi bi-calendar-range-fill me-2 text-primary"></i> <strong>结束时间:</strong> {{ $activity->end_time->format('Y年m月d日 H:i') }}</li>
                        <li class="mb-2"><i class="bi bi-geo-alt me-2 text-primary"></i> <strong>活动地点:</strong> {{ $activity->location }}</li>
                        <li class="mb-2"><i class="bi bi-people me-2 text-primary"></i> <strong>限制人数:</strong> {{ $activity->capacity }} 人</li>
                        <li class="mb-2"><i class="bi bi-person-check me-2 text-primary"></i> <strong>创建者:</strong> {{ $activity->creator->name }}</li> 
                    </ul>
                </div>
            </div>
        </div>

        {{-- 右侧：操作卡片 (报名/签到/取消) - 仅对非管理员显示 --}}
        @if ($isNotAdmin)
            <div class="col-md-4">
                <div class="card shadow-lg sticky-top border-0" style="top: 20px;">
                    {{-- 卡片头部使用主题色 --}}
                    <div class="card-header bg-primary text-white text-center fw-bold fs-5">
                        活动操作
                    </div>
                    <div class="card-body p-4 text-center">
                        {{-- 假设控制器已传入 $registration (用户报名记录) 和 $canCheckin (是否可签到) --}}

                        @if($registration)
                            {{-- 已报名提示使用 success --}}
                            <div class="alert alert-success shadow-sm mb-3">
                                <i class="bi bi-check-circle-fill me-1"></i> 您已报名此活动
                            </div>
                            
                            <p class="fw-semibold mb-3">当前状态: <span class="text-primary">{{ $registration->status }}</span></p>

                            @if($registration->status == 'registered' && $status != 'completed' && $status != 'cancelled')
                                {{-- 取消报名按钮使用 danger --}}
                                <form action="{{ route('registrations.destroy', $registration->id) }}" method="POST" onsubmit="return confirm('确定要取消报名吗？此操作不可撤销。');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100 btn-lg shadow">
                                        <i class="bi bi-x-octagon me-1"></i> 取消报名
                                    </button>
                                </form>
                            @endif

                        @if(isset($hasCheckedIn) && $hasCheckedIn)
                            <button class="btn btn-success w-100 mt-2" disabled>
                                <i class="bi bi-check-circle-fill"></i> 您已完成签到
                            </button>

                            @elseif($canCheckin)
                                <a href="{{ route('checkin.create', $activity->id) }}" class="btn btn-primary w-100 mt-2">
                                    <i class="bi bi-qr-code-scan"></i> 前往签到
                                </a>
                            @endif

                        @else
                            {{-- 未报名时的操作 --}}
                            <div class="alert alert-info shadow-sm mb-4">
                                <i class="bi bi-info-circle me-1"></i> 您尚未报名此活动。
                            </div>
                            
                            @if($status == 'published')
                                <form action="{{ route('registrations.store', $activity->id) }}" method="POST">
                                    @csrf
                                    {{-- 立即报名按钮使用 primary 主题色 --}}
                                    <button type="submit" class="btn btn-primary w-100 btn-lg shadow">
                                        <i class="bi bi-person-plus me-1"></i> 立即报名
                                    </button>
                                </form>
                            @else
                                {{-- 禁用按钮使用 secondary 灰色 --}}
                                <button type="button" class="btn btn-secondary w-100 btn-lg" disabled>
                                    {{ $badgeText }}，暂不开放报名
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
