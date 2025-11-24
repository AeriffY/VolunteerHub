@extends('layouts.app')

@section('title', '编辑活动')

@section('content')
    <h1>编辑活动: {{ $activity->title }}</h1> [cite: 1, 297]
    
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.activities.update', $activity->id) }}" method="POST">
                @method('PUT') [cite: 298]
                @include('admin.activities._form', ['activity' => $activity, 'submitButtonText' => '更新活动'])
            </form>
        </div>
    </div>
@endsection