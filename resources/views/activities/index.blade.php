@extends('layouts.app')

@section('title', '活动广场')

@section('content')
    <h1 class="mb-5 fw-bold border-bottom pb-3 custom-title">
        <i class="bi bi-compass me-2"></i> 活动广场
    </h1>

    {{-- 搜索表单 (保持简洁) --}}
    <form action="{{ route('activities.index') }}" method="GET" class="mb-5 p-4 bg-white rounded-4 shadow-sm">
        <div class="input-group input-group-lg">
            <input type="text" name="search" class="form-control border-end-0 border-primary" placeholder="搜索活动标题、地点..." value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
            @if(request('search'))
                <a href="{{ route('activities.index') }}" class="btn btn-outline-danger"><i class="bi bi-x-lg"></i> 清除</a>
            @endif
        </div>
    </form>

    @if($activities->count() > 0)
        {{-- 使用响应式 Grid 网格布局 --}}
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($activities as $activity)
                <div class="col">
                    {{-- 卡片容器：使用自定义的 .activity-card 类来添加悬停效果 --}}
                    <div class="card activity-card shadow border-0 h-100 rounded-4 overflow-hidden d-flex flex-column">
                            
                        {{-- 状态标签和时间信息 (顶部条) --}}
                        <div class="p-3 d-flex justify-content-between align-items-center text-white" 
                                style="background-color: #38c172;">
                                
                            @php
                                // 沿用精简配色逻辑
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
                            <span class="badge {{ $badgeClass }} fs-7">{{ $badgeText }}</span>
                            <small class="fw-bold"><i class="bi bi-clock me-1"></i> 开始时间：{{ $activity->start_time->diffForHumans() }} </small>
                        </div>

                        <div class="card-body p-4">
                            {{-- 标题 --}}
                            <h5 class="card-title fw-bold text-dark mb-2">{{ $activity->title }}</h5>
                            
                            {{-- 摘要 --}}
                            <p class="card-text text-muted mb-3">{{ Str::limit($activity->description, 70) }}</p>
                            
                            {{-- 关键信息列表 --}}
                            <div class="list-unstyled small fw-semibold">
                                <p class="mb-1 text-info"><i class="bi bi-geo-alt-fill me-2"></i> {{ $activity->location }}</p>
                                <p class="mb-1 text-secondary"><i class="bi bi-calendar-event me-2"></i> {{ $activity->start_time->format('m-d H:i') }}</p>
                                <p class="mb-0 text-success"><i class="bi bi-people-fill me-2"></i> 报名人数：{{ $activity->registrations->count() }} / {{ $activity->capacity }}</p>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-0 p-4 pt-0">
                            <div class="d-flex flex-wrap gap-2">
                                {{-- 查看详情 --}}
                                <a href="{{ route('activities.show', $activity->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> 查看
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- 分页链接美化 --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $activities->links() }}
        </div>

    @else
        <div class="alert alert-info shadow-sm py-4 rounded-3" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            @if(request('search'))
                没有找到符合条件的活动。请尝试使用其他关键词。
            @else
                目前活动广场暂无活动发布。
            @endif
        </div>
    @endif
@endsection
