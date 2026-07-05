<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سجل حضوري</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-[#f5f0e8] min-h-screen">

    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-clipboard-list text-blue-600"></i>
                سجل حضوري
            </h1>
            <p class="text-gray-600">تابع حضورك وأنشطتك</p>
        </div>

        <!-- إحصائيات -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6 border-r-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">الحاضرون</p>
                        <h3 class="text-3xl font-bold text-green-600">{{ $totalPresent }}</h3>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6 border-r-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">إجمالي الأنشطة</p>
                        <h3 class="text-3xl font-bold text-blue-600">{{ $totalActivities }}</h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6 border-r-4 border-gold" style="border-color: #d4a017;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">نسبة الحضور</p>
                        <h3 class="text-3xl font-bold" style="color: #d4a017;">{{ number_format($attendanceRate, 1) }}%</h3>
                    </div>
                    <div class="p-3 rounded-full" style="background-color: #fef3c7;">
                        <i class="fas fa-chart-pie text-2xl" style="color: #d4a017;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- جدول الحضور -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-history text-gray-600 ml-2"></i>
                    سجل الحضور التفصيلي
                </h2>
            </div>

            @if($attendanceRecords->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">النشاط</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">تاريخ الحضور</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">الحالة</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">النقاط</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($attendanceRecords as $record)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800">{{ $record->activity->title ?? 'نشاط محذوف' }}</div>
                                        <div class="text-sm text-gray-500">{{ $record->activity->location ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $record->check_in_time ? $record->check_in_time->format('Y/m/d H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($record->status === 'present')
                                            <span class="px-3 py-1 text-xs font-bold bg-green-100 text-green-700 rounded-full">
                                                حاضر
                                            </span>
                                        @elseif($record->status === 'late')
                                            <span class="px-3 py-1 text-xs font-bold bg-yellow-100 text-yellow-700 rounded-full">
                                                متأخر
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-bold bg-red-100 text-red-700 rounded-full">
                                                غائب
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold" style="color: #d4a017;">
                                            +{{ $record->points_earned ?? 0 }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-4 border-t border-gray-200">
                    {{ $attendanceRecords->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-500 text-lg">لا توجد سجلات حضور بعد</p>
                    <p class="text-gray-400 text-sm mt-2">ابدأ بالحضور في الأنشطة لرؤية سجلاتك هنا</p>
                </div>
            @endif
        </div>

        <!-- أزرار -->
        <div class="flex gap-3 mt-6">
            <a href="{{ url('/student/dashboard') }}" 
               class="flex-1 bg-white text-gray-800 py-3 rounded-xl hover:bg-gray-50 transition font-bold shadow text-center">
                <i class="fas fa-arrow-right ml-2"></i>
                العودة للوحة التحكم
            </a>
            <a href="{{ url('/attendance/scan') }}" 
               class="flex-1 py-3 rounded-xl transition font-bold shadow text-center text-white" 
               style="background-color: #d4a017;">
                <i class="fas fa-qrcode ml-2"></i>
                مسح QR Code
            </a>
        </div>
    </div>

</body>
</html>