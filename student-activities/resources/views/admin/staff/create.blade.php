@extends('layouts.admin')

@section('content')
<div class="staff-form">
    <div class="page-header">
        <h1><i class="fas fa-user-plus"></i> إضافة مشرف جديد</h1>
        <a href="{{ route('admin.staff') }}" class="btn-secondary">
            <i class="fas fa-arrow-right"></i> رجوع
        </a>
    </div>

    <div class="form-container">
        <form method="POST" action="{{ route('admin.staff.store') }}">
            @csrf

            <div class="form-group">
                <label>الاسم الكامل *</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
                @error('name') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>البريد الإلكتروني *</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
                @error('email') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>كلمة المرور *</label>
                <input type="password" name="password" required>
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>المنصب</label>
                    <input type="text" name="position" value="{{ old('position') }}" placeholder="مثال: مشرف أنشطة">
                </div>

                <div class="form-group">
                    <label>القسم</label>
                    <input type="text" name="department" value="{{ old('department') }}" placeholder="مثال: شؤون الطلاب">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>رقم الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone') }}">
                </div>

                <div class="form-group">
                    <label>تاريخ التعيين</label>
                    <input type="date" name="hire_date" value="{{ old('hire_date') }}">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> حفظ المشرف
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
    .form-group input {
        width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px;
        font-size: 14px; font-family: inherit;
    }
    .form-group input:focus { outline: none; border-color: #4f46e5; }
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