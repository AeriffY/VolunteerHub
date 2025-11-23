@extends('layouts.app')

@section('title', $activity->title)

@section('content')
    <h1 class="fw-bold mb-3">{{ $activity->title }}</h1>
    
    <div class="row g-5">
        <div class="col-md-8">
            <article class="blog-post">
                <h4 class="pb-2 border-bottom">活动描述</h4>
                <p>{{ $activity->description }}</p>
                
                <h4 class="mt-4 pb-2 border-bottom">活动详情</h4>
                <ul class="list-unstyled">
                    <li><i class="bi bi-calendar-range" style="width: 24px;"></i> <strong>开始时间:</strong> {{ $activity->start_time->format('Y年m月d日 H:i') }}</li>
                    <li><i class="bi bi-calendar-range-fill" style="width: 24px;"></i> <strong>结束时间:</strong> {{ $activity->end_time->format('Y年m月d日 H:i') }}</li>
                    <li><i class="bi bi-geo-alt" style="width: 24px;"></i> <strong>活动地点:</strong> {{ $activity->location }}</li>
                    <li><i class="bi bi-people" style="width: 24px;"></i> <strong>限制人数:</strong> {{ $activity->capacity }} 人</li>
                    <li><i class="bi bi-person-check" style="width: 24px;"></i> <strong>创建者:</strong> {{ $activity->creator->name }}</li> </ul>
            </article>
        </div>

        <div class="col-md-4">
            <div class="position-sticky" style="top: 2rem;">
                <div class="p-4 mb-3 bg-light rounded">
                    <h4 class="fst-italic">报名操作</h4>
                    
                    {{-- 
                        以下逻辑需要控制器传入 $registration 和 $canCheckin 变量
                        $registration = 当前用户在此活动的报名记录 (registrations 表)
                        $canCheckin = bool, 是否已报名且活动正在进行中
                    --}}

                    @if($registration)
                        <p class="text-success">您已报名此活动 (状态: {{ $registration->status }})</p>
                        
                        @if($registration->status == 'registered')
                            <form action="{{ route('registrations.destroy', $registration->id) }}" method="POST" onsubmit="return confirm('确定要取消报名吗？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-warning w-100">取消报名</button>
                            </form>
                        @endif

                        @if($canCheckin)
                             <a href="{{ route('checkin.create', $activity->id) }}" class="btn btn-success w-100 mt-2">
                                <i class="bi bi-qr-code-scan"></i> 前往签到
                             </a>
                        @endif

                    @else
                        <p>您尚未报名此活动。</p>
                        <form action="{{ route('registrations.store', $activity->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">立即报名</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection