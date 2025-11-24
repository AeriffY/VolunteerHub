@extends('layouts.app')

@section('title', '发布新活动')

@section('content')
    <h1>发布新活动</h1> [cite: 1, 291]
    
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.activities.store') }}" method="POST">
                @include('admin.activities._form', ['submitButtonText' => '发布活动'])
            </form>
        </div>
    </div>
@endsection