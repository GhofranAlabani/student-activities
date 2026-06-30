<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير الأنشطة</title>
    <style>
        body {
            font-family: 'Cairo', Arial, sans-serif;
            direction: rtl;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #d4a017;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #0a1929;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            color: #666;
            margin: 10px 0 0 0;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
        }
        .stat-box {
            background: #f5f0e8;
            padding: 15px 25px;
            border-radius: 10px;
            text-align: center;
            border-right: 4px solid #d4a017;
        }
        .stat-box h3 {
            margin: 0;
            font-size: 24px;
            color: #0a1929;
        }
        .stat-box p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table thead {
            background: #0a1929;
            color: white;
        }
        table th, table td {
            padding: 10px;
            text-align: right;
            border: 1px solid #ddd;
            font-size: 12px;
        }
        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #999;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير الأنشطة</h1>
        <p>المشرف: {{ $user->name }}</p>
        <p>تاريخ التقرير: {{ now()->format('Y/m/d H:i') }}</p>
        @if($fromDate || $toDate)
            <p>الفترة: {{ $fromDate ?? 'البداية' }} - {{ $toDate ?? 'الآن' }}</p>
        @endif
    </div>

    <div class="stats">
        <div class="stat-box">
            <h3>{{ $totalActivities }}</h3>
            <p>إجمالي الأنشطة</p>
        </div>
        <div class="stat-box">
            <h3>{{ $totalRegistrations }}</h3>
            <p>إجمالي التسجيلات</p>
        </div>
        <div class="stat-box">
            <h3>{{ $totalApproved }}</h3>
            <p>التسجيلات المقبولة</p>
        </div>
        <div class="stat-box">
            <h3>{{ number_format($avgRating, 1) }} ⭐</h3>
            <p>متوسط التقييم</p>
        </div>
    </div>

    <h2 style="color: #0a1929; border-bottom: 2px solid #d4a017; padding-bottom: 10px;">قائمة الأنشطة</h2>
    
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>العنوان</th>
                <th>النوع</th>
                <th>التاريخ</th>
                <th>المكان</th>
                <th>المسجلين</th>
                <th>النقاط</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $index => $activity)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $activity->title }}</td>
                    <td>{{ $activity->activityType->name ?? 'عام' }}</td>
                    <td>{{ \Carbon\Carbon::parse($activity->date)->format('Y/m/d') }}</td>
                    <td>{{ $activity->location }}</td>
                    <td>{{ $activity->registrations->count() }} / {{ $activity->max_participants ?? '∞' }}</td>
                    <td>{{ $activity->points ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>تم إنشاء هذا التقرير تلقائياً من منصة الأنشطة الطلابية</p>
    </div>
</body>
</html>