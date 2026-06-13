<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير إحصائيات الاستبيانات</title>
    <style>
        @font-face {
            font-family: 'Cairo';
            src: url('https://fonts.gstatic.com/s/cairo/v11/SLXGc1nY6HkvangZVmpQd3Bt.woff2') format('woff2');
        }
        body {
            font-family: 'Cairo', Arial, sans-serif;
            direction: rtl;
            margin: 20px;
            background: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #6366f1;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4f46e5;
            font-size: 28px;
            margin: 0;
        }
        .header p {
            color: #6b7280;
            margin: 10px 0 0 0;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .stat-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
            margin: 0;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            background: #4f46e5;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background: #eef2ff;
            color: #4f46e5;
            padding: 12px;
            text-align: right;
            font-weight: bold;
            border: 1px solid #c7d2fe;
        }
        td {
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            border-top: 2px solid #e5e7eb;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 تقرير إحصائيات الاستبيانات</h1>
        <p>نظام الأنشطة الطلابية - {{ date('Y/m/d') }}</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card" style="background: linear-gradient(135deg, #8B5CF6 0%, #6D28D9 100%);">
            <h3>إجمالي الأسئلة</h3>
            <div class="number">{{ $totalQuestions }}</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);">
            <h3>إجمالي الإجابات</h3>
            <div class="number">{{ $totalResponses }}</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
            <h3>عدد الطلاب</h3>
            <div class="number">{{ $totalStudents }}</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);">
            <h3>الأنشطة</h3>
            <div class="number">{{ $totalActivities }}</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">📈 توزيع الإجابات</div>
        <table>
            <thead>
                <tr>
                    <th>الإجابة</th>
                    <th>العدد</th>
                    <th>النسبة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($answersDistribution as $item)
                    @php
                        $percentage = $totalResponses > 0 ? round(($item->count / $totalResponses) * 100, 2) : 0;
                    @endphp
                    <tr>
                        <td>{{ $item->answer }}</td>
                        <td>{{ $item->count }}</td>
                        <td>{{ $percentage }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">📋 تفاصيل الإجابات حسب السؤال</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>السؤال</th>
                    <th>الإجمالي</th>
                    <th>موافق</th>
                    <th>محايد</th>
                    <th>أوافق بشدة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questionsWithAnswers as $index => $question)
                    @php
                        $total = $question->responses->sum('count');
                        $agree = $question->responses->firstWhere('answer', 'موافق')?->count ?? 0;
                        $neutral = $question->responses->firstWhere('answer', 'محايد')?->count ?? 0;
                        $strongAgree = $question->responses->firstWhere('answer', 'أوافق بشدة')?->count ?? 0;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $question->question }}</td>
                        <td style="font-weight: bold; color: #4f46e5;">{{ $total }}</td>
                        <td>{{ $agree }}</td>
                        <td>{{ $neutral }}</td>
                        <td>{{ $strongAgree }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>تم إنشاء هذا التقرير آلياً من نظام الأنشطة الطلابية</p>
        <p>تاريخ الإنشاء: {{ date('Y/m/d H:i') }}</p>
    </div>
</body>
</html>