@extends('layouts.admin')

@section('content')
<div class="announcement-form">
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> تعديل الإعلان</h1>
        <a href="{{ route('admin.announcements') }}" class="btn-secondary">
            <i class="fas fa-arrow-right"></i> رجوع
        </a>
    </div>

    <div class="form-container">
        <form method="POST" action="{{ route('admin.announcements.update', $announcement->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>عنوان الإعلان *</label>
                <input type="text" name="title" value="{{ old('title', $announcement->title) }}" required>
                @error('title') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>محتوى الإعلان *</label>
                <textarea name="content" rows="5" required>{{ old('content', $announcement->content) }}</textarea>
                @error('content') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>نوع الإعلان *</label>
                    <select name="type" required>
                        <option value="activity" {{ $announcement->type === 'activity' ? 'selected' : '' }}>إعلان نشاط</option>
                        <option value="general" {{ $announcement->type === 'general' ? 'selected' : '' }}>إعلان عام</option>
                        <option value="warning" {{ $announcement->type === 'warning' ? 'selected' : '' }}>تحذير</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>الحالة</label>
                    <select name="is_active" required>
                        <option value="1" {{ $announcement->is_active ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ !$announcement->is_active ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>تاريخ الانتهاء</label>
                <input type="date" name="expires_at" value="{{ old('expires_at', $announcement->expires_at ? $announcement->expires_at->format('Y-m-d') : '') }}">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .page-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;
    }
    .page-header h1 { color: #1e3a8a; font-size: 28px; }
    .btn-secondary {
        background: #6b7280; color: white; padding: 12px 24px;
        border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
    }
    .form-container {
        background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #374151; }
    .form-group input, .form-group select, .form-group textarea {
        width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px;
        font-size: 14px; font-family: inherit;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        outline: none; border-color: #4f46e5;
    }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .error { color: #dc2626; font-size: 13px; margin-top: 5px; display: block; }
    .form-actions { margin-top: 30px; }
    .btn-submit {
        background: #4f46e5; color: white; padding: 14px 30px; border: none;
        border-radius: 8px; cursor: pointer; font-size: 16px; font-family: inherit;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-submit:hover { background: #4338ca; }
</style>
@endsection