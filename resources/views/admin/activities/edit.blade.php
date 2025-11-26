@extends('layouts.app')

@section('title', '编辑活动')

@section('content')
    <h1 class="fw-bold custom-title mb-4">
        <i class="bi bi-pencil-square me-2"></i> 编辑活动: {{ $activity->title }}
    </h1>
    
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('admin.activities.update', $activity->id) }}" method="POST">
                @method('PUT')
                @include('admin.activities._form', ['activity' => $activity, 'submitButtonText' => '更新活动'])
            </form>
        </div>
    </div>
@endsection
