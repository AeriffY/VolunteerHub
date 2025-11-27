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
                        <i class="bi bi-award me-1"></i> 我的志愿者等级
                    </div>
                    <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                        @php
                            $totalHours = (float)($hours->total_hours ?? 0);
                            // 定义五级等级规则（纯勋章/星星体系，无奖杯）
                            $levels = [
                                ['name' => '初心志愿者', 'threshold' => 0, 'next_threshold' => 5, 'icon' => 'bi-star', 'color' => '#6c757d'],
                                ['name' => '成长志愿者', 'threshold' => 5, 'next_threshold' => 20, 'icon' => 'bi-star-half', 'color' => '#198754'],
                                ['name' => '星光志愿者', 'threshold' => 20, 'next_threshold' => 50, 'icon' => 'bi-star-fill', 'color' => '#0d6efd'],
                                ['name' => '先锋志愿者', 'threshold' => 50, 'next_threshold' => 100, 'icon' => 'bi-award', 'color' => '#6f42c1'],
                                ['name' => '领航志愿者', 'threshold' => 100, 'next_threshold' => null, 'icon' => 'bi-award-fill', 'color' => '#ffc107'],
                            ];

                            // 匹配当前等级
                            $currentLevel = $levels[0]; // 默认初心志愿者
                            foreach ($levels as $level) {
                                if ($totalHours >= $level['threshold']) {
                                    $currentLevel = $level;
                                }
                            }
                            // 判断是否为最高等级
                            $isHighestLevel = $currentLevel['name'] === '领航志愿者';
                            // 计算下一级进度（非最高级时）
                            $nextThreshold = $currentLevel['next_threshold'];
                            $progress = !$isHighestLevel ? ($totalHours) / ($nextThreshold) * 100 : 100;
                            $progress = min($progress, 100); // 进度不超过100%
                            
                            // 找到下一级名称（非最高级时）
                            $nextLevelName = '';
                            if (!$isHighestLevel) {
                                $currentIndex = array_search($currentLevel, $levels);
                                $nextLevelName = $levels[$currentIndex + 1]['name'];
                            }
                        @endphp

                        {{-- 当前等级展示 --}}
                        <div class="py-3">
                            {{-- 等级图标（纯勋章/星星系，无奖杯） --}}
                            <i class="bi {{ $currentLevel['icon'] }} mb-3" style="font-size: 4rem; color: {{ $currentLevel['color'] }};"></i>
                            <h4 class="fw-bolder mb-1" style="color: {{ $currentLevel['color'] }};">🏅 {{ $currentLevel['name'] }}</h4>
                            
                            {{-- 等级说明+进度（非最高级） --}}
                            @if (!$isHighestLevel)
                                <p class="text-muted small mb-1">
                                    累计服务时长达到 {{ $nextThreshold }}.00 小时可解锁「{{ $nextLevelName }}」
                                </p>
                                <div class="w-75 mx-auto mb-2">
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%; background-color: {{ $currentLevel['color'] }};" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <p class="fw-bold mb-0 text-primary">
                                    当前进度: {{ number_format($totalHours, 2) }} / {{ $nextThreshold }}.00 小时
                                </p>
                            @else
                                {{-- 最高等级提示（勋章表述） --}}
                                <p class="text-muted small mb-0">🎉 已达成最高志愿者勋章等级，感谢您的无私奉献！</p>
                            @endif
                        </div>
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
        // 沿用精简配色逻辑
            $status = $reg->activity->status ?? 'draft';
            $statusMap = [
                'published' => ['bg-success', '报名中'],
                'in_progress' => ['bg-info', '进行中'],
                'completed' => ['bg-secondary', '已结束'],
                'cancelled' => ['bg-danger', '已取消'],
                'draft' => ['bg-warning', '待发布'],
            ];
            $badgeClass = $statusMap[$status][0] ?? 'bg-secondary';
            $badgeText = $statusMap[$status][1] ?? '未知';
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
            <span class="badge {{ $badgeClass }} fs-7">{{ $badgeText }}</span>
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
