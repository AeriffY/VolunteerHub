{{-- 
    这个文件被 create.blade.php 和 edit.blade.php 包含
    需要传入变量:
    $activity (可选, 仅在编辑时)
--}}

@csrf

<div class="mb-3">
    <label for="title" class="form-label">活动标题</label> [cite: 293]
    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $activity->title ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="description" class="form-label">活动描述</label>
    <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $activity->description ?? '') }}</textarea>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="start_time" class="form-label">活动开始时间</label>
        <input type="datetime-local" class="form-control" id="start_time" name="start_time" value="{{ old('start_time', isset($activity->start_time) ? $activity->start_time->format('Y-m-d\TH:i') : '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="end_time" class="form-label">活动结束时间</label>
        <input type="datetime-local" class="form-control" id="end_time" name="end_time" value="{{ old('end_time', isset($activity->end_time) ? $activity->end_time->format('Y-m-d\TH:i') : '') }}" required>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mb-3">
        <label for="location" class="form-label">活动地点</label>
        <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $activity->location ?? '') }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label for="capacity" class="form-label">限制人数</label>
        <input type="number" class="form-control" id="capacity" name="capacity" value="{{ old('capacity', $activity->capacity ?? '') }}" min="1" required>
    </div>
</div>

<div class="mb-3">
    <label for="status" class="form-label">活动状态</label>
    <select class="form-select" id="status" name="status">
        <option value="draft" @selected(old('status', $activity->status ?? 'draft') == 'draft')>草稿 (Draft)</option>
        <option value="published" @selected(old('status', $activity->status ?? '') == 'published')>发布 (Published)</option>
        <option value="in_progress" @selected(old('status', $activity->status ?? '') == 'in_progress')>进行中 (In Progress)</option>
        <option value="completed" @selected(old('status', $activity->status ?? '') == 'completed')>已完成 (Completed)</option>
        <option value="cancelled" @selected(old('status', $activity->status ?? '') == 'cancelled')>已取消 (Cancelled)</option>
    </select>
</div>

<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">取消</a>
    <button type="submit" class="btn btn-primary">{{ $submitButtonText ?? '提交' }}</button> [cite: 295, 302]
</div>