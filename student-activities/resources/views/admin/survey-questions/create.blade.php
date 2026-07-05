@extends('layouts.admin')

@section('content')

<style>
    .create-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 30px;
    }

    .create-header {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .btn-back {
        background: #f3f4f6;
        color: #4b5563;
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.3s;
        border: 2px solid #e5e7eb;
    }

    .btn-back:hover {
        background: #e5e7eb;
        transform: translateX(-3px);
    }

    .create-header .icon-box {
        background: linear-gradient(135deg, #7c3aed, #4f46e5);
        color: white;
        padding: 12px;
        border-radius: 12px;
        display: inline-flex;
    }

    .create-header h1 {
        font-size: 24px;
        font-weight: bold;
        color: #1f2937;
        margin: 0;
    }

    .create-form {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        font-size: 15px;
    }

    .form-group label span {
        color: #ef4444;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    select.form-control {
        cursor: pointer;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 30px;
        padding-top: 25px;
        border-top: 2px solid #f3f4f6;
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #4b5563;
        padding: 12px 24px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
    }

    .btn-submit {
        background: linear-gradient(135deg, #7c3aed, #4f46e5);
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 10px rgba(124, 58, 237, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(124, 58, 237, 0.4);
    }

    .error-message {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #dc2626;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .error-message ul {
        margin: 0;
        padding-right: 20px;
    }

    .error-message li {
        margin: 5px 0;
    }
</style>

<div class="create-container">
    
    <!-- Header with Back Button -->
    <div class="create-header">
        <div class="header-right">
            <a href="{{ route('admin.survey-questions.index') }}" class="btn-back" title="رجوع">
                <i class="fas fa-arrow-right"></i>
            </a>
            <span class="icon-box">
                <i class="fas fa-plus-circle"></i>
            </span>
            <h1>إضافة سؤال جديد</h1>
        </div>
        <div>
            <!-- مساحة فارغة للتوازن -->
        </div>
    </div>

    <!-- Form -->
    <div class="create-form">
        
        @if($errors->any())
        <div class="error-message">
            <strong><i class="fas fa-exclamation-circle"></i> هناك أخطاء في البيانات:</strong>
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.survey-questions.store') }}" method="POST">
            @csrf

            <!-- السؤال -->
            <div class="form-group">
                <label>
                    نص السؤال <span>*</span>
                </label>
                <textarea 
                    name="question" 
                    class="form-control" 
                    placeholder="أدخل نص السؤال هنا..."
                    required
                >{{ old('question') }}</textarea>
            </div>

            <!-- القسم -->
            <div class="form-group">
                <label>
                    القسم <span>*</span>
                </label>
                <select name="section" class="form-control" required>
                    <option value="">اختر القسم...</option>
                    <option value="تقييم المدرب" {{ old('section') == 'تقييم المدرب' ? 'selected' : '' }}>تقييم المدرب</option>
                    <option value="المادة العلمية" {{ old('section') == 'المادة العلمية' ? 'selected' : '' }}>المادة العلمية</option>
                    <option value="البيئة التدريبية" {{ old('section') == 'البيئة التدريبية' ? 'selected' : '' }}>البيئة التدريبية</option>
                    <option value="التنظيم والإدارة" {{ old('section') == 'التنظيم والإدارة' ? 'selected' : '' }}>التنظيم والإدارة</option>
                    <option value="أخرى" {{ old('section') == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                </select>
            </div>

            <!-- نوع السؤال -->
            <div class="form-group">
                <label>
                    نوع الإجابة <span>*</span>
                </label>
                <select name="answer_type" class="form-control" required>
                    <option value="multiple_choice">اختيار من متعدد</option>
                    <option value="yes_no">نعم / لا</option>
                    <option value="rating">تقييم (1-5)</option>
                    <option value="text">نص حر</option>
                </select>
            </div>

            <!-- الترتيب -->
            <div class="form-group">
                <label>
                    الترتيب
                </label>
                <input 
                    type="number" 
                    name="order" 
                    class="form-control" 
                    value="{{ old('order', 1) }}"
                    min="1"
                    placeholder="رقم الترتيب"
                >
            </div>

            <!-- ملاحظات -->
            <div class="form-group">
                <label>
                    ملاحظات
                </label>
                <textarea 
                    name="notes" 
                    class="form-control" 
                    placeholder="أي ملاحظات إضافية..."
                >{{ old('notes') }}</textarea>
            </div>

            <!-- الأزرار -->
            <div class="form-actions">
                <a href="{{ route('admin.survey-questions.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i>
                    إلغاء
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i>
                    حفظ السؤال
                </button>
            </div>
        </form>
    </div>
</div>

@endsection