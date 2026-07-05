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
    
    // حساب عدد الطلاب من الإجابات الفريدة
    $totalStudents = $responses->unique('student_id')->count();
    
    // حساب نسبة المشاركة
    if ($totalQuestions > 0 && $totalStudents > 0) {
        $maxPossibleResponses = $totalQuestions * $totalStudents;
        $participationRate = $maxPossibleResponses > 0 ? round(($totalResponses / $maxPossibleResponses) * 100, 2) : 0;
    } else {
        $participationRate = 0;
    }
    
    // حساب توزيع الإجابات
    $agreeCount = $responses->whereIn('answer', ['موافق', 'أوافق بشدة'])->count();
    $neutralCount = $responses->where('answer', 'محايد')->count();
    $disagreeCount = $responses->whereIn('answer', ['لا أوافق', 'لا اوافق'])->count();
    
    // إحصائيات كل سؤال (عرض أول 5 أسئلة فقط)
    $limitedQuestions = $questions->take(5); // غير الرقم هنا لعرض عدد مختلف
    $questionsStats = $limitedQuestions->map(function($question) use ($responses) {
        $questionResponses = $responses->where('question_id', $question->id);
        $total = $questionResponses->count();
        
        $agree = $questionResponses->whereIn('answer', ['موافق', 'أوافق بشدة'])->count();
        $neutral = $questionResponses->where('answer', 'محايد')->count();
        $disagree = $questionResponses->whereIn('answer', ['لا أوافق', 'لا اوافق'])->count();
        
        return [
            'question' => $question->question ?? $question->question_text ?? 'سؤال',
            'answers' => [
                [
                    'label' => 'موافق',
                    'count' => $agree,
                    'percentage' => $total > 0 ? round(($agree / $total) * 100) : 0,
                    'class' => 'agree'
                ],
                [
                    'label' => 'محايد',
                    'count' => $neutral,
                    'percentage' => $total > 0 ? round(($neutral / $total) * 100) : 0,
                    'class' => 'neutral'
                ],
                [
                    'label' => 'لا أوافق',
                    'count' => $disagree,
                    'percentage' => $total > 0 ? round(($disagree / $total) * 100) : 0,
                    'class' => 'disagree'
                ]
            ],
            'total' => $total
        ];
    });
    
    // التفاصيل الكاملة
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

<style>
    /* ===== الحاوية الرئيسية ===== */
    .stats-container {
        width: 100%;
        padding: 30px;
        max-width: 100%;
        direction: rtl;
    }

    /* ===== الهيدر ===== */
    .stats-header {
        background: linear-gradient(135deg, #7c3aed, #4f46e5);
        border-radius: 20px;
        padding: 30px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 8px 25px rgba(124, 58, 237, 0.3);
    }

    .stats-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .stats-header h1 {
        font-size: 32px;
        font-weight: bold;
        margin: 0 0 10px 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stats-header h1 i {
        background: rgba(255,255,255,0.2);
        padding: 12px;
        border-radius: 12px;
    }

    .stats-header p {
        margin: 0;
        opacity: 0.95;
        font-size: 16px;
    }

    .header-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-print {
        background: white;
        color: #7c3aed;
        padding: 12px 24px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        border: none;
        cursor: pointer;
    }

    .btn-print:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    /* ===== بطاقات الإحصائيات ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 5px;
        height: 100%;
    }

    .stat-card.purple::before { background: #a855f7; }
    .stat-card.green::before { background: #22c55e; }
    .stat-card.blue::before { background: #3b82f6; }
    .stat-card.orange::before { background: #f97316; }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .stat-card-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .stat-info h3 {
        margin: 0 0 8px 0;
        font-size: 14px;
        color: #6b7280;
        font-weight: 500;
    }

    .stat-value {
        margin: 0;
        font-size: 36px;
        font-weight: bold;
        color: #1f2937;
    }

    .stat-sub {
        margin: 5px 0 0 0;
        font-size: 13px;
        color: #9ca3af;
    }

    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        flex-shrink: 0;
    }

    .stat-card.purple .stat-icon {
        background: linear-gradient(135deg, #f3e8ff, #e9d5ff);
        color: #a855f7;
    }

    .stat-card.green .stat-icon {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #22c55e;
    }

    .stat-card.blue .stat-icon {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #3b82f6;
    }

    .stat-card.orange .stat-icon {
        background: linear-gradient(135deg, #ffedd5, #fed7aa);
        color: #f97316;
    }

    /* ===== قسم الرسم البياني ===== */
    .chart-section {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 22px;
        font-weight: bold;
        color: #1f2937;
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-title i {
        color: #7c3aed;
    }

    .chart-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 30px;
        flex-wrap: wrap;
        padding: 15px;
    }

    #answersChart {
        max-width: 250px;
        max-height: 250px;
    }

    .chart-legend {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #f9fafb;
        border-radius: 10px;
        font-size: 14px;
    }

    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 4px;
    }

    .legend-color.agree { background: #3b82f6; }
    .legend-color.neutral { background: #a855f7; }
    .legend-color.disagree { background: #ef4444; }

    /* ===== قائمة الأسئلة ===== */
    .questions-section {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        margin-bottom: 30px;
    }

    .question-stats {
        margin-bottom: 20px;
        padding: 15px;
        background: #f9fafb;
        border-radius: 12px;
        border-right: 4px solid #7c3aed;
        transition: all 0.3s;
    }

    .question-stats:hover {
        background: #f3f4f6;
        transform: translateX(-3px);
    }

    .question-title {
        font-weight: bold;
        color: #1f2937;
        font-size: 15px;
        margin: 0 0 12px 0;
    }

    .answers-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .answer-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 12px;
        background: white;
        border-radius: 8px;
    }

    .answer-label {
        min-width: 100px;
        font-weight: 600;
        color: #4b5563;
        font-size: 13px;
    }

    .answer-bar {
        flex: 1;
        height: 26px;
        background: #e5e7eb;
        border-radius: 6px;
        overflow: hidden;
        position: relative;
    }

    .answer-bar-fill {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding: 0 8px;
        color: white;
        font-weight: bold;
        font-size: 12px;
        transition: width 0.5s;
    }

    .answer-bar-fill.agree { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .answer-bar-fill.neutral { background: linear-gradient(135deg, #a855f7, #7c3aed); }
    .answer-bar-fill.disagree { background: linear-gradient(135deg, #ef4444, #dc2626); }

    /* ===== الجدول التفصيلي ===== */
    .details-table {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow-x: auto;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead {
        background: linear-gradient(135deg, #7c3aed, #4f46e5);
        color: white;
    }

    .data-table th {
        padding: 12px;
        text-align: center;
        font-weight: 600;
        font-size: 13px;
    }

    .data-table td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #f3f4f6;
        font-size: 13px;
    }

    .data-table tbody tr:hover {
        background: #f9fafb;
    }

    .data-table tbody tr:nth-child(even) {
        background: #f9fafb;
    }

    .data-table tbody tr:nth-child(even):hover {
        background: #f3f4f6;
    }

    .table-number {
        background: #7c3aed;
        color: white;
        width: 26px;
        height: 26px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 12px;
    }

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

    /* ===== التجاوب ===== */
    @media (max-width: 768px) {
        .stats-container {
            padding: 15px;
        }
        .stats-header h1 {
            font-size: 24px;
        }
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .chart-container {
            flex-direction: column;
        }
        #answersChart {
            max-width: 200px;
            max-height: 200px;
        }
        .data-table {
            font-size: 11px;
        }
        .data-table th,
        .data-table td {
            padding: 6px;
        }
    }
</style>

<div class="stats-container">
    
    <!-- Header -->
    <div class="stats-header">
        <div class="stats-header-content">
            <div>
                <h1>
                    <i class="fas fa-chart-pie"></i>
                    إحصائيات الاستبيانات
                </h1>
                <p>تحليل شامل لاستجابات الطلاب وتقييماتهم</p>
            </div>
            <div class="header-actions">
                <button onclick="window.print()" class="btn-print">
                    <i class="fas fa-print"></i>
                    طباعة / حفظ PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card purple">
            <div class="stat-card-content">
                <div class="stat-info">
                    <h3>إجمالي الأسئلة</h3>
                    <p class="stat-value">{{ $totalQuestions }}</p>
                    <p class="stat-sub">سؤال</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-card-content">
                <div class="stat-info">
                    <h3>إجمالي الإجابات</h3>
                    <p class="stat-value">{{ $totalResponses }}</p>
                    <p class="stat-sub">إجابة</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="stat-card blue">
            <div class="stat-card-content">
                <div class="stat-info">
                    <h3>عدد الطلاب</h3>
                    <p class="stat-value">{{ $totalStudents }}</p>
                    <p class="stat-sub">طالب</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="stat-card orange">
            <div class="stat-card-content">
                <div class="stat-info">
                    <h3>نسبة المشاركة</h3>
                    <p class="stat-value">{{ $participationRate }}%</p>
                    <p class="stat-sub">مشاركة</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-percentage"></i>
                </div>
            </div>
        </div>
    </div>

    @if($totalResponses > 0)
    <!-- Chart Section -->
    <div class="chart-section">
        <h2 class="section-title">
            <i class="fas fa-chart-donut"></i>
            توزيع الإجابات العام
        </h2>
        <div class="chart-container">
            <canvas id="answersChart" width="200" height="200"></canvas>
            <div class="chart-legend">
                <div class="legend-item">
                    <div class="legend-color agree"></div>
                    <span>موافق ({{ $agreeCount }})</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color neutral"></div>
                    <span>محايد ({{ $neutralCount }})</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color disagree"></div>
                    <span>لا أوافق ({{ $disagreeCount }})</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Questions Stats -->
    <div class="questions-section">
        <h2 class="section-title">
            <i class="fas fa-list-alt"></i>
            الإجابات على كل سؤال (أول 5 أسئلة)
        </h2>
        
        @foreach($questionsStats as $question)
        <div class="question-stats">
            <h3 class="question-title">{{ $question['question'] }}</h3>
            <div class="answers-list">
                @foreach($question['answers'] as $answer)
                <div class="answer-item">
                    <span class="answer-label">{{ $answer['label'] }}</span>
                    <div class="answer-bar">
                        <div class="answer-bar-fill {{ $answer['class'] }}" style="width: {{ $answer['percentage'] }}%">
                            {{ $answer['count'] }} ({{ $answer['percentage'] }}%)
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <!-- Detailed Table -->
    <div class="details-table">
        <h2 class="section-title">
            <i class="fas fa-table"></i>
            التفاصيل الكاملة للإجابات
        </h2>
        <div class="table-responsive">
            <table class="data-table">
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
                    @foreach($detailedStats as $index => $stat)
                    <tr>
                        <td><span class="table-number">{{ $index + 1 }}</span></td>
                        <td style="text-align: right; font-weight: 500;">{{ $stat['question'] }}</td>
                        <td>{{ $stat['agree'] }}</td>
                        <td>{{ $stat['neutral'] }}</td>
                        <td>{{ $stat['disagree'] }}</td>
                        <td><strong>{{ $stat['total'] }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-chart-bar"></i>
        <p style="font-size: 20px; font-weight: 600; color: #4b5563;">لا توجد إجابات بعد</p>
        <p style="font-size: 14px; color: #6b7280;">ستظهر الإحصائيات بعد أن يجيب الطلاب على الاستبيان</p>
    </div>
    @endif
</div>

@if($totalResponses > 0)
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('answersChart').getContext('2d');
    const answersChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['موافق', 'محايد', 'لا أوافق'],
            datasets: [{
                data: [{{ $agreeCount }}, {{ $neutralCount }}, {{ $disagreeCount }}],
                backgroundColor: ['#3b82f6', '#a855f7', '#ef4444'],
                borderWidth: 3,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.parsed || 0;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
</script>
@endif

@endsection