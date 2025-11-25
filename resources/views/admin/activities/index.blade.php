@extends('layouts.app')

@section('title', '活动管理')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>活动管理</h1>
        <!-- 页面顶部的“创建新活动”按钮 -->
        <a href="{{ route('admin.activities.create') }}" class="btn btn-success">
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

    <form action="{{ route('admin.activities.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="搜索活动标题、地点..." value="{{ request('search') }}">
            <button class="btn btn-outline-primary" type="submit">搜索</button>
            @if(request('search'))
                {{-- 注意：这里应该是 admin.activities.index，而不是 activities.index --}}
                <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-danger">清除</a>
            @endif
        </div>
    </form>

    @if($activities->count() > 0)
        <div class="list-group">
            @foreach($activities as $activity)
                {{-- 根据状态设置不同的背景颜色，增强视觉效果 --}}
                <div class="list-group-item list-group-item-action mb-3 shadow-sm 
                    @if($activity->status == 'cancelled') bg-light text-muted border-danger @endif
                ">
                    <div class="d-flex w-100 justify-content-between align-items-start">
                        <div>
                            <h5 class="mb-1">
                                {{ $activity->title }}
                                {{-- 显示活动状态 Badge --}}
                                @php
                                    $badgeClass = [
                                        'published' => 'bg-success',
                                        'in_progress' => 'bg-info text-dark',
                                        'completed' => 'bg-secondary',
                                        'cancelled' => 'bg-danger',
                                        'draft' => 'bg-warning text-dark',
                                    ][$activity->status] ?? 'bg-light text-dark';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($activity->status) }}</span>
                            </h5>
                            <p class="mb-1 text-muted">{{ Str::limit($activity->description, 150) }}</p>
                            <small>
                                <i class="bi bi-geo-alt-fill"></i> {{ $activity->location }} | 
                                <i class="bi bi-calendar-event"></i> {{ $activity->start_time->format('Y-m-d H:i') }} |
                                <i class="bi bi-people-fill"></i> {{ $activity->registrations->count() }} / {{ $activity->capacity }} 人
                            </small>
                        </div>
                        
                        <!-- 活动右侧的操作按钮组 -->
                        <div class="btn-group">
                            <a href="{{ route('admin.activities.edit', $activity->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i> 编辑
                            </a>
                            
                            {{-- 【新增】取消活动按钮，仅在活动未被取消或完成时显示 --}}
                            @if($activity->status != 'cancelled' && $activity->status != 'completed')
                                {{-- 注意：这里我们遵循了您代码中已有的 confirm() 模式。在生产环境中，应使用 Bootstrap Modal 替代。 --}}
                                <form action="{{ route('admin.activities.destroy', $activity->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE') 
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('确定要取消活动: {{ $activity->title }} 吗？此操作不可撤销。')">
                                        <i class="bi bi-x-circle"></i> 取消活动
                                    </button>
                                </form>
                            @endif

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
            @endforeach
        </div>
        
        {{-- 分页链接 --}}
        <div class="mt-4">
            {{ $activities->links() }}
        </div>
    @else
        <div class="alert alert-info" role="alert">
            @if(request('search'))
                没有找到符合条件的活动。
            @else
                目前没有活动，请创建新活动。
            @endif
        </div>
    @endif
@endsection