@extends('layouts.admin')

@section('content')

<!-- Header (Top Bar) - مطابق لإدارة الطلاب -->
<header class="bg-[#0f172a] rounded-2xl p-4 mb-8 shadow-lg flex justify-between items-center text-white relative overflow-hidden">
    
    <!-- اليمين: العنوان -->
    <div class="flex items-center gap-3 z-10">
        <i class="fas fa-poll text-amber-500 text-3xl"></i>
        <div>
            <h1 class="text-2xl font-bold text-white mb-1">إدارة أسئلة الاستبيان</h1>
            <p class="text-slate-400 text-sm">إضافة وتعديل وحذف أسئلة الاستبيان العام</p>
        </div>
    </div>

    <!-- اليسار: التاريخ + زر الوضع الليلي -->
    <div class="flex flex-col items-end gap-2 z-10">
        
        <!-- التاريخ -->
        <div class="bg-slate-800 px-4 py-2 rounded-xl border border-slate-700 flex items-center gap-2">
            <span class="font-semibold text-sm">{{ now()->format('d/m/Y') }}</span>
            <i class="far fa-calendar-alt text-amber-500"></i>
        </div>

        <!-- زر الوضع الليلي -->
        <button onclick="toggleDarkMode()" 
                class="w-9 h-9 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-400 hover:text-amber-500 hover:border-amber-500 transition-all shadow-md"
                title="تبديل الوضع الليلي">
            <i class="fas fa-moon text-xs" id="darkModeIcon"></i>
        </button>

    </div>
</header>

<!-- بطاقات الإحصائيات -->
<div class="stats-cards">
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-question"></i>
        </div>
        <div class="stat-info">
            <h3>إجمالي الأسئلة</h3>
            <p class="stat-number">{{ $questions->count() ?? 0 }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <h3>الحالة</h3>
            <p class="stat-number" style="font-size: 24px; color: #166534;">نشط</p>
            <small class="text-slate-500">الاستبيان متاح</small>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <h3>آخر تحديث</h3>
            <p class="stat-number" style="font-size: 20px;">{{ now()->format('Y/m/d') }}</p>
            <small class="text-slate-500">{{ now()->format('H:i') }}</small>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-folder"></i>
        </div>
        <div class="stat-info">
            <h3>الأقسام</h3>
            <p class="stat-number">{{ $questions->groupBy('category')->count() ?? 0 }}</p>
        </div>
    </div>
</div>

<!-- حاوية الجدول -->
<div class="table-container">
    <!-- شريط البحث والفلترة -->
    <div class="table-header">
        <h2 class="table-title">قائمة الأسئلة</h2>
        <div class="flex gap-3">
            <form action="{{ route('admin.survey-questions.reset') }}" method="POST" onsubmit="return confirm('هل أنت متأكد من إعادة الأسئلة الافتراضية؟');">
                @csrf
                <button type="submit" class="btn-reset-custom">
                    <i class="fas fa-redo"></i> الأسئلة الافتراضية
                </button>
            </form>
            <a href="{{ route('admin.survey-questions.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> إضافة سؤال
            </a>
        </div>
    </div>

    <!-- الجدول -->
    <div class="table-responsive">
        <table class="students-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>السؤال</th>
                    <th>القسم</th>
                    <th>تاريخ الإضافة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $index => $question)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="user-info">
                            <div class="avatar" style="background: #6d28d9">
                                <i class="fas fa-question"></i>
                            </div>
                            <div>
                                <div class="user-name">{{ $question->question ?? 'غير محدد' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="role-badge student">
                            {{ $question->category ?? 'عام' }}
                        </span>
                    </td>
                    <td>{{ $question->created_at ? $question->created_at->format('Y/m/d H:i') : 'غير محدد' }}</td>
                    <td>
                        <div class="actions-buttons">
                            <a href="{{ route('admin.survey-questions.edit', $question->id) }}" class="action-btn edit" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.survey-questions.destroy', $question->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا السؤال؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="no-data">
                        <i class="fas fa-inbox"></i>
                        <p>لا توجد أسئلة مسجلة حالياً</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

<style>
    /* نفس الـ CSS اللي في صفحة الطلاب */
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 15px;
        font-size: 24px;
        color: white;
    }

    .stat-icon.purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
    .stat-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-icon.blue { background: linear-gradient(135deg, #3b82f6, #1e40af); }
    .stat-icon.orange { background: linear-gradient(135deg, #f97316, #ea580c); }

    .stat-info h3 {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .stat-number {
        font-size: 32px;
        font-weight: 900;
        color: #1e3a8a;
        margin: 0;
        line-height: 1;
    }

    .table-container {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .table-title {
        color: #1e3a8a;
        font-size: 20px;
        margin: 0;
    }

    .btn-add {
        padding: 10px 20px;
        background: #4f46e5;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-add:hover {
        background: #4338ca;
    }

    .btn-reset-custom {
        padding: 10px 20px;
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-reset-custom:hover {
        background: #2563eb;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .students-table {
        width: 100%;
        border-collapse: collapse;
    }

    .students-table th,
    .students-table td {
        padding: 14px 12px;
        text-align: right;
        border-bottom: 1px solid #e5e7eb;
    }

    .students-table th {
        background: #f9fafb;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }

    .students-table tbody tr:hover {
        background: #f9fafb;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
        flex-shrink: 0;
    }

    .user-name {
        font-weight: 600;
        color: #111827;
    }

    .role-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .role-badge.student {
        background: #dbeafe;
        color: #1e40af;
    }

    .actions-buttons {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        text-decoration: none;
    }

    .action-btn.edit {
        background: #fef3c7;
        color: #d97706;
    }

    .action-btn.edit:hover {
        background: #d97706;
        color: white;
    }

    .action-btn.delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .action-btn.delete:hover {
        background: #dc2626;
        color: white;
    }

    .no-data {
        text-align: center;
        padding: 40px 20px;
        color: #9ca3af;
    }

    .no-data i {
        font-size: 48px;
        margin-bottom: 10px;
        display: block;
    }
</style>

<script>
// دالة تبديل الوضع الليلي
function toggleDarkMode() {
    const html = document.documentElement;
    const icon = document.getElementById('darkModeIcon');
    
    if (html.classList.contains('dark')) {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
        icon.className = 'fas fa-moon text-xs';
    } else {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
        icon.className = 'fas fa-sun text-xs';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const html = document.documentElement;
    const icon = document.getElementById('darkModeIcon');
    const savedTheme = localStorage.getItem('theme');
    
    if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        html.classList.add('dark');
        icon.className = 'fas fa-sun text-xs';
    }
});
</script>