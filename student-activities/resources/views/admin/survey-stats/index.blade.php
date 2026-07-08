@extends('layouts.admin')

@section('content')

@php
    // حساب الإحصائيات من قاعدة البيانات
    use App\Models\SurveyQuestion;
    use App\Models\SurveyResponse;
    
    $questions = SurveyQuestion::all();
    $responses = SurveyResponse::all();
    
    $totalQuestions = $questions->count();
    $totalResponses = $responses->count();
    $totalStudents = $responses->unique('student_id')->count();
    
    if ($totalQuestions > 0 && $totalStudents > 0) {
        $maxPossibleResponses = $totalQuestions * $totalStudents;
        $participationRate = $maxPossibleResponses > 0 ? round(($totalResponses / $maxPossibleResponses) * 100, 2) : 0;
    } else {
        $participationRate = 0;
    }
    
    $agreeCount = $responses->whereIn('answer', ['موافق', 'أوافق بشدة'])->count();
    $neutralCount = $responses->where('answer', 'محايد')->count();
    $disagreeCount = $responses->whereIn('answer', ['لا أوافق', 'لا اوافق'])->count();
    
    $limitedQuestions = $questions->take(5);
    $questionsStats = $limitedQuestions->map(function($question) use ($responses) {
        $questionResponses = $responses->where('question_id', $question->id);
        $total = $questionResponses->count();
        $agree = $questionResponses->whereIn('answer', ['موافق', 'أوافق بشدة'])->count();
        $neutral = $questionResponses->where('answer', 'محايد')->count();
        $disagree = $questionResponses->whereIn('answer', ['لا أوافق', 'لا اوافق'])->count();
        
        return [
            'question' => $question->question ?? $question->question_text ?? 'سؤال',
            'answers' => [
                ['label' => 'موافق', 'count' => $agree, 'percentage' => $total > 0 ? round(($agree / $total) * 100) : 0, 'class' => 'agree'],
                ['label' => 'محايد', 'count' => $neutral, 'percentage' => $total > 0 ? round(($neutral / $total) * 100) : 0, 'class' => 'neutral'],
                ['label' => 'لا أوافق', 'count' => $disagree, 'percentage' => $total > 0 ? round(($disagree / $total) * 100) : 0, 'class' => 'disagree']
            ],
            'total' => $total
        ];
    });
    
    $detailedStats = $questionsStats->map(function($stat) {
        return [
            'question' => $stat['question'],
            'agree' => $stat['answers'][0]['count'],
            'neutral' => $stat['answers'][1]['count'],
            'disagree' => $stat['answers'][2]['count'],
            'total' => $stat['total']
        ];
    });
@endphp

<!-- Header (Top Bar) - مطابق لإدارة الطلاب -->
<header class="bg-[#0f172a] rounded-2xl p-4 mb-8 shadow-lg flex justify-between items-center text-white relative overflow-hidden">
    
    <!-- اليمين: العنوان -->
    <div class="flex items-center gap-3 z-10">
        <i class="fas fa-chart-pie text-amber-500 text-3xl"></i>
        <div>
            <h1 class="text-2xl font-bold text-white mb-1">إحصائيات الاستبيان</h1>
            <p class="text-slate-400 text-sm">تحليل شامل لاستجابات الطلاب وتقييماتهم</p>
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
            <p class="stat-number">{{ $totalQuestions ?? 0 }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <h3>إجمالي الإجابات</h3>
            <p class="stat-number">{{ $totalResponses ?? 0 }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3>عدد الطلاب</h3>
            <p class="stat-number">{{ $totalStudents ?? 0 }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-percentage"></i>
        </div>
        <div class="stat-info">
            <h3>نسبة المشاركة</h3>
            <p class="stat-number">{{ $participationRate ?? 0 }}%</p>
        </div>
    </div>
</div>

<!-- حاوية المحتوى -->
<div class="table-container">
    
    @if($totalResponses > 0)
    
    <!-- شريط الأدوات -->
    <div class="table-header">
        <h2 class="table-title">توزيع الإجابات العام</h2>
        <div class="flex gap-3">
            <a href="{{ route('admin.survey-stats.export.pdf') }}" class="btn-export">
                <i class="fas fa-file-pdf"></i> تصدير PDF
            </a>
            <a href="{{ route('admin.survey-stats.export.excel') }}" class="btn-export-excel">
                <i class="fas fa-file-excel"></i> تصدير Excel
            </a>
        </div>
    </div>

    <!-- الرسم البياني -->
    <div class="chart-container mb-6">
        <canvas id="responsesChart"></canvas>
    </div>

    <!-- جدول التفاصيل -->
    <div class="table-responsive">
        <table class="students-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>السؤال</th>
                    <th>موافق</th>
                    <th>محايد</th>
                    <th>لا أوافق</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detailedStats as $index => $stat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="user-info">
                            <div class="avatar" style="background: #6d28d9">
                                <i class="fas fa-question"></i>
                            </div>
                            <div>
                                <div class="user-name">{{ $stat['question'] ?? 'غير محدد' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="role-badge student">
                            {{ $stat['agree'] ?? 0 }}
                        </span>
                    </td>
                    <td>
                        <span class="role-badge" style="background: #fef3c7; color: #d97706;">
                            {{ $stat['neutral'] ?? 0 }}
                        </span>
                    </td>
                    <td>
                        <span class="role-badge" style="background: #fee2e2; color: #dc2626;">
                            {{ $stat['disagree'] ?? 0 }}
                        </span>
                    </td>
                    <td class="font-bold">{{ $stat['total'] ?? 0 }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="no-data">
                        <i class="fas fa-inbox"></i>
                        <p>لا توجد إجابات مسجلة حالياً</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @else
    <!-- رسالة في حال عدم وجود بيانات -->
    <div class="text-center py-12">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4">
            <i class="fas fa-chart-bar text-2xl"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-700">لا توجد إجابات بعد</h3>
        <p class="text-slate-500 mt-1">ستظهر الإحصائيات بعد أن يجيب الطلاب على الاستبيان</p>
    </div>
    @endif

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

    .btn-export {
        padding: 10px 20px;
        background: #dc2626;
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

    .btn-export:hover {
        background: #b91c1c;
    }

    .btn-export-excel {
        padding: 10px 20px;
        background: #16a34a;
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

    .btn-export-excel:hover {
        background: #15803d;
    }

    .chart-container {
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
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

    /* Dark Mode Support */
    html.dark .students-table th {
        background-color: #334155 !important;
        color: #f1f5f9 !important;
    }
    
    html.dark .students-table tbody tr {
        border-bottom-color: #475569;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    // رسم البيان الدائري
    const ctx = document.getElementById('responsesChart');
    if (ctx) {
        new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['موافق', 'محايد', 'لا أوافق'],
                datasets: [{
                    data: [{{ $agreeCount ?? 0 }}, {{ $neutralCount ?? 0 }}, {{ $disagreeCount ?? 0 }}],
                    backgroundColor: ['#3b82f6', '#a855f7', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: 'Cairo', size: 14 }
                        }
                    }
                }
            }
        });
    }
});
</script>