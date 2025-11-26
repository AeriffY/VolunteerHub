{{-- 
    这个文件被 create.blade.php 和 edit.blade.php 包含
    需要传入变量:
    $activity (可选, 仅在编辑时)
    $submitButtonText (必须)
--}}

@csrf

<div class="mb-4">
    <label for="title" class="form-label fw-bold">活动标题 <span class="text-danger">*</span></label>
    <input type="text" class="form-control shadow-sm rounded-3" id="title" name="title" value="{{ old('title', $activity->title ?? '') }}" required>
    @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

<div class="mb-4">
    <label for="description" class="form-label fw-bold">活动描述 <span class="text-danger">*</span></label>
    <textarea class="form-control shadow-sm rounded-3" id="description" name="description" rows="6" required>{{ old('description', $activity->description ?? '') }}</textarea>
    @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="start_time" class="form-label fw-bold">活动开始时间 <span class="text-danger">*</span></label>
        <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time', isset($activity->start_time) ? $activity->start_time->format('Y-m-d\TH:i') : '') }}" required>
        @error('start_time')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div> 
    <div class="col-md-6">
        <label for="end_time" class="form-label fw-bold">活动结束时间 <span class="text-danger">*</span></label>
        <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time', isset($activity->end_time) ? $activity->end_time->format('Y-m-d\TH:i') : '') }}" required>
        @error('end_time')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-8">
        <label for="location" class="form-label fw-bold">活动地点 <span class="text-danger">*</span></label>
        <input type="text" class="form-control shadow-sm rounded-3" id="location" name="location" value="{{ old('location', $activity->location ?? '') }}" required>
        @error('location')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="capacity" class="form-label fw-bold">限制人数 <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity', $activity->capacity ?? '') }}" min="1" required>
        @error('capacity')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<div class="mb-5">
    <label for="status" class="form-label fw-bold">活动状态</label>
    <select class="form-select shadow-sm rounded-3" id="status" name="status">
        <option value="draft" @selected(old('status', $activity->status ?? 'draft') == 'draft')>草稿 (Draft)</option>
        <option value="published" @selected(old('status', $activity->status ?? '') == 'published')>发布 (Published)</option>
        <option value="in_progress" @selected(old('status', $activity->status ?? '') == 'in_progress')>进行中 (In Progress)</option>
        <option value="completed" @selected(old('status', $activity->status ?? '') == 'completed')>已完成 (Completed)</option>
        <option value="cancelled" @selected(old('status', $activity->status ?? '') == 'cancelled')>已取消 (Cancelled)</option>
    </select>
    @error('status')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

<div class="d-grid">
    <button type="submit" class="btn btn-primary btn-lg rounded-3 shadow-lg">
        <i class="bi bi-send-fill me-2"></i> {{ $submitButtonText }}
    </button>
</div>
