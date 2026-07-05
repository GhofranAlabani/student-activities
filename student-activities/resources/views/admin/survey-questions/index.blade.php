@extends('layouts.admin')

@section('content')

<style>
    /* ===== الحاوية الرئيسية ===== */
    .survey-container {
        width: 100%;
        padding: 30px;
        max-width: 100%;
        direction: rtl;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* ===== قسم الهيدر ===== */
    .survey-header {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .survey-header-content {
        flex: 1;
        min-width: 300px;
    }

    .survey-header h1 {
        font-size: 28px;
        font-weight: bold;
        color: #1f2937;
        margin: 0 0 10px 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .survey-header h1 .icon-box {
        background: linear-gradient(135deg, #7c3aed, #4f46e5);
        color: white;
        padding: 12px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(124, 58, 237, 0.3);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .survey-header p {
        color: #6b7280;
        margin: 0;
        font-size: 15px;
    }

    .header-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-reset {
        background: #2563eb;
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
        transition: all 0.3s;
        text-decoration: none;
    }

    .btn-reset:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(37, 99, 235, 0.4);
    }

    .btn-add {
        background: linear-gradient(135deg, #7c3aed, #4f46e5);
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 10px rgba(124, 58, 237, 0.3);
        transition: all 0.3s;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(124, 58, 237, 0.4);
    }

    /* ===== رسالة النجاح ===== */
    .success-alert {
        background: #f0fdf4;
        border-right: 4px solid #22c55e;
        color: #166534;
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .success-alert i {
        color: #22c55e;
        font-size: 22px;
    }

    /* ===== بطاقات الإحصائيات ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        border-radius: 16px;
        padding: 25px;
        color: white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .stat-card.purple { background: linear-gradient(135deg, #a855f7, #9333ea); }
    .stat-card.blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .stat-card.green { background: linear-gradient(135deg, #22c55e, #16a34a); }
    .stat-card.orange { background: linear-gradient(135deg, #f97316, #ea580c); }

    .stat-info p:first-child {
        margin: 0 0 8px 0;
        font-size: 14px;
        opacity: 0.9;
    }

    .stat-info .stat-value {
        margin: 0;
        font-size: 36px;
        font-weight: bold;
    }

    .stat-info .stat-sub {
        margin: 5px 0 0 0;
        font-size: 13px;
        opacity: 0.9;
    }

    .stat-icon {
        width: 65px;
        height: 65px;
        background: rgba(255,255,255,0.2);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        backdrop-filter: blur(10px);
    }

    /* ===== صندوق الأسئلة ===== */
    .questions-box {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .questions-header {
        background: linear-gradient(135deg, #7c3aed, #4f46e5);
        padding: 20px 25px;
        color: white;
    }

    .questions-header h2 {
        margin: 0;
        font-size: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .question-item {
        padding: 25px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        transition: all 0.3s;
        border-right: 4px solid transparent;
    }

    .question-item:hover {
        background: #f9fafb;
        border-right-color: #7c3aed;
    }

    .question-item:last-child {
        border-bottom: none;
    }

    .question-right {
        display: flex;
        gap: 15px;
        flex: 1;
        min-width: 0;
    }

    .question-number {
        background: linear-gradient(135deg, #a855f7, #4f46e5);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 18px;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(124, 58, 237, 0.3);
    }

    .question-details {
        flex: 1;
        min-width: 0;
    }

    .question-text {
        font-weight: bold;
        color: #1f2937;
        font-size: 17px;
        margin: 0 0 10px 0;
        word-break: break-word;
    }

    .question-meta {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        font-size: 13px;
        color: #6b7280;
    }

    .question-meta span {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .question-meta i {
        color: #9ca3af;
    }

    .question-actions {
        display: flex;
        gap: 10px;
        flex-shrink: 0;
    }

    .btn-edit, .btn-delete {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        font-size: 16px;
    }

    .btn-edit {
        background: #eff6ff;
        color: #2563eb;
    }

    .btn-edit:hover {
        background: #2563eb;
        color: white;
    }

    .btn-delete {
        background: #fef2f2;
        color: #dc2626;
    }

    .btn-delete:hover {
        background: #dc2626;
        color: white;
    }

    /* ===== الحالة الفارغة ===== */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    .empty-state i {
        font-size: 70px;
        color: #d1d5db;
        margin-bottom: 15px;
    }

    .empty-state p {
        margin: 5px 0;
    }

    .btn-add-first {
        display: inline-block;
        background: #7c3aed;
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        margin-top: 15px;
        transition: all 0.3s;
    }

    .btn-add-first:hover {
        background: #6d28d9;
    }

    /* ===== صندوق المعلومات ===== */
    .info-box {
        background: linear-gradient(to left, #eff6ff, #eef2ff);
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        gap: 15px;
        align-items: flex-start;
    }

    .info-icon {
        width: 50px;
        height: 50px;
        background: #dbeafe;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #2563eb;
        font-size: 22px;
    }

    .info-content h3 {
        margin: 0 0 8px 0;
        color: #1e3a8a;
        font-size: 17px;
    }

    .info-content p {
        margin: 0;
        color: #1e40af;
        line-height: 1.6;
    }

    /* ===== التجاوب ===== */
    @media (max-width: 768px) {
        .survey-container {
            padding: 15px;
        }
        .survey-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .header-buttons {
            width: 100%;
        }
        .question-item {
            flex-direction: column;
        }
        .question-actions {
            width: 100%;
            justify-content: flex-end;
        }
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="survey-container">
    
    <!-- Header Section -->
    <div class="survey-header">
        <div class="survey-header-content">
            <h1>
                <span class="icon-box">
                    <i class="fas fa-poll"></i>
                </span>
                إدارة أسئلة الاستبيان العام
            </h1>
            <p>يمكنك إضافة أو تعديل أو حذف أسئلة الاستبيان</p>
        </div>
        <div class="header-buttons">
            <form action="{{ route('admin.survey-questions.reset') }}" method="POST" 
                  onsubmit="return confirm('هل أنت متأكد من إعادة الأسئلة الافتراضية؟ سيتم حذف جميع الأسئلة الحالية!')">
                @csrf
                <button type="submit" class="btn-reset">
                    <i class="fas fa-redo"></i>
                    <span>الأسئلة الافتراضية</span>
                </button>
            </form>
            <a href="{{ route('admin.survey-questions.create') }}" class="btn-add">
                <i class="fas fa-plus"></i>
                <span>إضافة سؤال</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="success-alert">
            <i class="fas fa-check-circle"></i>
            <span style="font-weight: 600;">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card purple">
            <div class="stat-info">
                <p>إجمالي الأسئلة</p>
                <p class="stat-value">{{ $questions->count() }}</p>
            </div>
            <div class="stat-icon">
                <i class="fas fa-question"></i>
            </div>
        </div>
        
        <div class="stat-card blue">
            <div class="stat-info">
                <p>آخر تحديث</p>
                <p class="stat-value" style="font-size: 22px;">
                    {{ $questions->max('created_at') ? $questions->max('created_at')->format('Y/m/d') : '-' }}
                </p>
                <p class="stat-sub">
                    {{ $questions->max('created_at') ? $questions->max('created_at')->format('H:i') : '' }}
                </p>
            </div>
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-info">
                <p>الحالة</p>
                <p class="stat-value" style="font-size: 24px;">نشط</p>
                <p class="stat-sub">الاستبيان متاح</p>
            </div>
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>

        <div class="stat-card orange">
            <div class="stat-info">
                <p>الأقسام</p>
                <p class="stat-value">{{ $questions->pluck('section')->unique()->count() }}</p>
            </div>
            <div class="stat-icon">
                <i class="fas fa-folder"></i>
            </div>
        </div>
    </div>

    <!-- Questions List -->
    <div class="questions-box">
        <div class="questions-header">
            <h2>
                <i class="fas fa-list-ul"></i>
                <span>قائمة الأسئلة ({{ $questions->count() }})</span>
            </h2>
        </div>

        @if($questions->count() > 0)
            @php $uniqueQuestions = $questions->unique('question')->values(); @endphp
            @foreach($uniqueQuestions as $index => $question)
            <div class="question-item">
                <div class="question-right">
                    <span class="question-number">{{ $index + 1 }}</span>
                    <div class="question-details">
                        <p class="question-text">{{ $question->question }}</p>
                        <div class="question-meta">
                            @if($question->section)
                            <span>
                                <i class="fas fa-folder"></i>
                                {{ $question->section }}
                            </span>
                            @endif
                            <span>
                                <i class="fas fa-calendar"></i>
                                {{ $question->created_at->format('Y/m/d') }}
                            </span>
                            <span>
                                <i class="fas fa-clock"></i>
                                {{ $question->created_at->format('H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="question-actions">
                    <a href="{{ route('admin.survey-questions.edit', $question->id) }}" 
                       class="btn-edit" title="تعديل">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.survey-questions.destroy', $question->id) }}" 
                          method="POST"
                          onsubmit="return confirm('هل تريد حذف هذا السؤال؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete" title="حذف">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p style="font-size: 20px; font-weight: 600; color: #4b5563;">لا توجد أسئلة</p>
                <p style="font-size: 14px; color: #6b7280;">أضف أسئلة جديدة أو استخدم الأسئلة الافتراضية</p>
                <a href="{{ route('admin.survey-questions.create') }}" class="btn-add-first">
                    <i class="fas fa-plus"></i> إضافة أول سؤال
                </a>
            </div>
        @endif
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <div class="info-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        <div class="info-content">
            <h3>معلومات مهمة</h3>
            <p>
                هذه الأسئلة ستظهر للطلاب بعد إكمال كل نشاط. يمكن للطلاب الإجابة بـ: 
                <strong>موافق</strong>، 
                <strong>محايد</strong>، أو 
                <strong>لا اوافق</strong>
            </p>
        </div>
    </div>
</div>

@endsection