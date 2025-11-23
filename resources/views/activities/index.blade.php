@extends('layouts.app')

@section('title', '活动广场')

@section('content')
    <h1 class="mb-4">活动广场</h1>

    <form action="{{ route('activities.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="搜索活动标题、地点..." value="{{ request('search') }}">
            <button class="btn btn-outline-primary" type="submit">搜索</button>
            @if(request('search'))
                <a href="{{ route('activities.index') }}" class="btn btn-outline-danger">清除</a>
            @endif
        </div>
    </form>

    @if($activities->count() > 0)
        <div class="list-group">
            @foreach($activities as $activity)
                <a href="{{ route('activities.show', $activity->id) }}" class="list-group-item list-group-item-action mb-3 shadow-sm">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{ $activity->title }}</h5>
                        <small class="text-muted">{{ $activity->start_time->diffForHumans() }}</small>
                    </div>
                    <p class="mb-1">{{ Str::limit($activity->description, 150) }}</p>
                    <small>
                        <i class="bi bi-geo-alt-fill"></i> {{ $activity->location }} | 
                        <i class="bi bi-calendar-event"></i> {{ $activity->start_time->format('Y-m-d H:i') }} |
                        <i class="bi bi-people-fill"></i> {{ $activity->capacity }} 人
                    </small>
                </a>
            @endforeach
        </div>

    @else
        <div class="alert alert-info" role="alert">
            @if(request('search'))
                没有找到符合条件的活动。
            @else
                目前没有已发布的活动。
            @endif
        </div>
    @endif
@endsection