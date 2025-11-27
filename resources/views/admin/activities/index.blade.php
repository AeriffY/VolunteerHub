@extends('layouts.app')
@section('title', '活动管理')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
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
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($activities as $activity)
            <div class="col">
                <div class="card activity-card shadow border-0 h-100 rounded-4 overflow-hidden d-flex flex-column">
                    
                    {{-- 状态标签和时间信息 (顶部条) --}}
                    <div class="p-3 d-flex justify-content-between align-items-center text-white" 
                        style="background-color: #38c172;">
                        
                        @php
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

                    {{-- ************** 核心内容区域：改为左右分栏 ************** --}}
                    <div class="card-body p-4">
                        <div class="row g-3">
                            
                            {{-- 左侧栏: 活动基本信息 (占据 7/12 宽度) --}}
                            <div class="col-md-7">
                                <h5 class="card-title fw-bold text-dark mb-2">{{ $activity->title }}</h5>
                                <p class="card-text text-muted mb-3">{{ Str::limit($activity->description, 70) }}</p>
                                <div class="list-unstyled small fw-semibold">
                                    <p class="mb-1 text-info"><i class="bi bi-geo-alt-fill me-2"></i> {{ $activity->location }}</p>
                                    <p class="mb-1 text-secondary"><i class="bi bi-calendar-event me-2"></i> {{ $activity->start_time->format('m-d H:i') }}</p>
                                    <p class="mb-0 text-success"><i class="bi bi-people-fill me-2"></i> 报名人数：{{ $activity->registrations->count() }} / {{ $activity->capacity }}</p>
                                </div>
                            </div>

                            {{-- 右侧栏: 签到码显示区域 (占据 5/12 宽度) --}}
                            <div class="col-md-5 d-flex align-items-center justify-content-center">
                                @if($activity->status == 'in_progress' && $activity->checkin_code)
                                    <div class="text-center p-3 border rounded-3 bg-light-subtle shadow-sm flex-grow-1" style="border-color: #38c172 !important;">
                                        <p class="mb-1 text-danger fw-bold small">当前签到码:</p>
                                        <span class="fs-4 fw-bolder text-success">
                                            {{ $activity->checkin_code }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- ************** 核心内容区域结束 ************** --}}
                    
                    {{-- **操作按钮区域**：使用 card-footer --}}
                    <div class="card-footer bg-light border-0 p-4 pt-0">
                        <div class="d-flex flex-wrap gap-2">
                            {{-- 查看详情 --}}
                            <a href="{{ route('activities.show', $activity->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> 查看
                            </a>

                            {{-- 编辑活动 --}}
                            @if($activity->status != 'completed' && $activity->status != 'cancelled')
                            <a href="{{ route('admin.activities.edit', $activity->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i> 编辑
                            </a>
                            @endif

                            {{-- 取消活动 --}}
                            @if($activity->status != 'completed' && $activity->status != 'cancelled')
                                <form action="{{ route('admin.activities.destroy', $activity->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE') 
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-x-circle"></i> 取消活动
                                    </button>
                                </form>
                            @endif

                            {{-- 生成签到码 --}}
                            @if($activity->status == 'in_progress')
                                {{-- 1. 为签到码表单添加 ID --}}
                                <form id="generateCodeForm_{{ $activity->id }}" action="{{ route('admin.activities.generatecode' , $activity->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    {{-- 2. 移除 onclick，改为触发模态框，并传递表单 ID --}}
                                    <button type="button" 
                                            class="btn btn-sm btn-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#confirmCodeGenerationModal"
                                            data-form-id="generateCodeForm_{{ $activity->id }}"> 
                                        <i class="bi bi-key"></i> 生成签到码
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
    
    
    {{-- =================================================================== --}}
    {{-- START: 样式优化后的签到码生成确认模态框 (Modal) 结构 --}}
    {{-- =================================================================== --}}
    <div class="modal fade" id="confirmCodeGenerationModal" tabindex="-1" aria-labelledby="confirmCodeGenerationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-success text-white rounded-top-4 py-3">
                    <h5 class="modal-title fw-bold fs-5" id="confirmCodeGenerationModalLabel"><i class="bi bi-key me-2"></i> 确认生成新的签到码</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2" style="font-size: 3rem;"></i>
                    <p class="mt-3 fs-5 fw-medium">您确定要为该活动重新生成新的签到码吗？</p>
                    <p class="text-danger fw-bold">提示：一旦生成，当前旧的签到码将立即失效。</p>
                </div>
                <div class="modal-footer justify-content-center border-top-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill w-40" data-bs-dismiss="modal">取消</button>
                    
                    <button type="button" class="btn btn-success rounded-pill w-40 fw-bold text-white" id="confirmGenerateCodeBtn">
                        确定
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    
    {{-- =================================================================== --}}
    {{-- START: JavaScript 逻辑 (修复提交逻辑) --}}
    {{-- =================================================================== --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // 确保Bootstrap已加载
        if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
            console.error('Bootstrap Modal未加载，请检查Bootstrap JS引入');
            return; // 若Bootstrap未加载，直接退出，避免后续错误
        }

        const modalElement = document.getElementById('confirmCodeGenerationModal');
        const confirmBtn = document.getElementById('confirmGenerateCodeBtn');
        let formToSubmit = null;

        if (modalElement) {
            // 监听模态框显示事件
            modalElement.addEventListener('show.bs.modal', function (event) {
                formToSubmit = null;
                const button = event.relatedTarget;
                if (button) {
                    const formId = button.getAttribute('data-form-id');
                    formToSubmit = formId ? document.getElementById(formId) : null;
                }
            });

            // 监听模态框关闭事件
            modalElement.addEventListener('hidden.bs.modal', function () {
                formToSubmit = null;
            });
        }

        if (confirmBtn && modalElement) {
            confirmBtn.addEventListener('click', function() {
                if (formToSubmit) {
                    // 关闭模态框并提交表单
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    modalInstance.hide();
                    formToSubmit.submit();
                }
            });
        }
    });
</script>

@endsection


