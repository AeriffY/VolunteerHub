@extends('layouts.app')
@section('title', '活动管理')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        {{-- 标题应用 custom-title 和圆润字体样式 --}}
        <h1 class="fw-bold custom-title mb-0">
            <i class="bi bi-clipboard-check me-2"></i> 活动管理
        </h1>
        <a href="{{ route('admin.activities.create') }}" class="btn btn-primary btn-lg shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> 创建新活动
        </a>
    </div>
    {{-- 消息提示 --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
    {{-- 搜索表单美化 --}}
    <form action="{{ route('admin.activities.index') }}" method="GET" class="mb-5 p-4 bg-white rounded-4 shadow-sm">
        <div class="input-group input-group-lg">
            <input type="text" name="search" class="form-control border-end-0 border-primary" placeholder="搜索活动标题、地点..." value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
            @if(request('search'))
                <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-danger"><i class="bi bi-x-lg"></i> 清除</a>
            @endif
        </div>
    </form>

    @if($activities->count() > 0)
        {{-- 使用响应式 Grid 网格布局 --}}
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($activities as $activity)
            <div class="col">
                {{-- 将 .activity-card 和 h-100 放在 card 上，并设置为 Flex 容器 --}}
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

                    <div class="card-body p-4 d-flex flex-column"> {{-- card-body 也设置为 flex-column --}}
                        {{-- 标题 --}}
                        <h5 class="card-title fw-bold text-dark mb-2">{{ $activity->title }}</h5>
                        
                        {{-- 摘要 --}}
                        <p class="card-text text-muted mb-3">{{ Str::limit($activity->description, 70) }}</p>
                        
                        {{-- 关键信息列表 --}}
                        <div class="list-unstyled small fw-semibold mt-auto"> {{-- 使用 mt-auto 将列表推到内容区域底部 --}}
                            <p class="mb-1 text-info"><i class="bi bi-geo-alt-fill me-2"></i> {{ $activity->location }}</p>
                            <p class="mb-1 text-secondary"><i class="bi bi-calendar-event me-2"></i> {{ $activity->start_time->format('m-d H:i') }}</p>
                            <p class="mb-0 text-success"><i class="bi bi-people-fill me-2"></i> 报名人数：{{ $activity->registrations->count() }} / {{ $activity->capacity }}</p>
                        </div>
                    </div>
                    
                    {{-- **操作按钮区域**：使用 card-footer --}}
                    <div class="card-footer bg-light border-0 p-4 pt-0">
                        <div class="d-flex flex-wrap gap-2">
                            {{-- 查看详情 --}}
                            <a href="{{ route('activities.show', $activity->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> 查看
                            </a>

                            {{-- 编辑活动 --}}
                            <a href="{{ route('admin.activities.edit', $activity->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i> 编辑
                            </a>

                            {{-- 取消活动（仅当状态不是已完成或已取消时显示） --}}
                            @if($activity->status != 'completed' && $activity->status != 'cancelled')
                                <form action="{{ route('admin.activities.destroy', $activity->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE') 
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('确定要取消活动: {{ $activity->title }} 吗？此操作不可撤销。')">
                                        <i class="bi bi-x-circle"></i> 取消活动
                                    </button>
                                </form>
                            @endif

                            {{-- 生成签到码（仅当活动进行中时显示） --}}
                            @if($activity->status == 'in_progress')
                                <form action="{{ route('admin.activities.generatecode' , $activity->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-info" onclick="return confirm('确定要生成新的签到码吗？旧签到码将失效。')">
                                        <i class="bi bi-key"></i> 签到码
                                    </button>
                                </form>
                            @endif
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
                目前没有活动，请创建新活动。
            @endif
        </div>
    @endif
@endsection
