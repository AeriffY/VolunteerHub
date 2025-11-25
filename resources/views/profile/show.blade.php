@extends('layouts.app')

@section('title', '个人中心')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>个人中心</h1>
        <a href="{{ route('profile.exportPdf') }}" class="btn btn-outline-danger">
            <i class="bi bi-file-earmark-pdf-fill"></i> 导出时长PDF
        </a>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="card-title">累计志愿服务时长</h5>
                    <p class="display-4 fw-bold">{{ $hours->total_hours ?? '0.00' }}</p>
                    <p class="card-text">小时</p>
                </div>
            </div>
        </div>
        
        {{-- 我的勋章卡片 --}}
        <div class="col-md-6 mb-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="card-title">我的勋章</h5>
                    
                    @php
                        $totalHours = (float)($hours->total_hours ?? 0);
                        $isExcellentVolunteer = $totalHours >= 10.0;
                    @endphp

                    @if ($isExcellentVolunteer)
                        <div class="d-flex align-items-center justify-content-center flex-column">
                            <img src="{{ asset('images/medal.png') }}" 
                                 alt="优秀志愿者勋章" 
                                 class="img-fluid mb-2 rounded-circle shadow-sm" 
                                 style="width: 80px; height: 80px; border: 3px solid #28a745;">
                            <p class="fw-bold text-success mb-0 mt-2">🏅 优秀志愿者</p>
                            <small class="text-muted">已达成 10 小时服务标准</small>
                        </div>
                    @else
                        <div class="text-center p-3">
                            <i class="bi bi-award text-secondary opacity-50" style="font-size: 3rem;"></i>
                            <p class="text-muted small mt-2 mb-0">
                                累计志愿服务时长达到 10 小时可解锁此勋章。
                            </p>
                            <p class="fw-bold mb-0">当前: {{ $totalHours }} 小时</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <h3 class="mt-4">我的活动记录</h3>
    <div class="list-group">
        {{-- 
            控制器应传入 $registrations (包含 activity 关联)
            查询 'registrations' 表中 'user_id' 为当前用户的记录
        --}}
        @forelse($registrations as $reg)
            <a href="{{ route('activities.show', $reg->activity->id) }}" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $reg->activity->title }}</h5>
                    <span class="badge bg-{{ $reg->status == 'registered' ? 'success' : 'secondary' }}">{{ $reg->status == 'registered' ? '已报名' : '已取消' }}</span>
                </div>
                <p class="mb-1">活动时间: {{ $reg->activity->start_time->format('Y-m-d') }}</p>
                <small>报名时间: {{ $reg->registration_time->format('Y-m-d') }}</small>
            </a>
        @empty
            <div class="alert alert-info">您还没有报名任何活动。</div>
        @endforelse
    </div>

@endsection
