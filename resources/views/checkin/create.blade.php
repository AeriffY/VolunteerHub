@extends('layouts.app')

@section('title', '活动签到')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header text-center bg-white">
                        <h4>活动签到</h4>
                        {{-- 确保 $activity 变量已从控制器传入 --}}
                        <p class="text-muted mb-0">{{ $activity->title ?? '未知活动' }}</p>
                    </div>
                    <div class="card-body p-4">
                        {{-- 签到说明 --}}
                        <div class="alert alert-info text-center mb-4 border-0">
                            <i class="bi bi-info-circle me-2"></i>
                            请输入活动现场的签到码完成签到
                        </div>

                        {{-- 签到码输入表单 --}}
                        <form action="{{ route('checkin.store', $activity->id) }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="checkin_code" class="form-label visually-hidden">签到码</label>
                                <input type="tel" {{-- 改进：改为 tel，在移动设备上弹出数字键盘 --}}
                                       class="form-control form-control-lg text-center fw-bold @error('checkin_code') is-invalid @enderror" 
                                       id="checkin_code" 
                                       name="checkin_code" 
                                       value="{{ old('checkin_code') }}" {{-- 保持旧值 --}}
                                       placeholder="请输入6位数字签到码" 
                                       maxlength="6"
                                       pattern="\d{6}" {{-- 改进：明确要求输入6个数字 --}}
                                       required
                                       autocomplete="off"> {{-- 阻止浏览器自动填充，保证输入新鲜度 --}}
                                
                                @error('checkin_code')
                                    <div class="invalid-feedback text-center mt-2">
                                        {{ $message }}
                                        <div class="small text-danger"></div> {{-- 将提示信息稍微弱化并居中 --}}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2 shadow">
                                <i class="bi bi-check-circle me-1"></i> 确认签到
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection