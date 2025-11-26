@extends('layouts.app')

@section('title', '发布新活动')

@section('content')
    <h1 class="fw-bold custom-title mb-4">
        <i class="bi bi-megaphone me-2"></i> 发布新活动
    </h1>
    
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('admin.activities.store') }}" method="POST">
                @include('admin.activities._form', ['submitButtonText' => '发布活动'])
            </form>
        </div>
    </div>
@endsection
