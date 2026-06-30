<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سجل الحضور</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-clipboard-list text-blue-600"></i>
                سجل الحضور
            </h1>
        </div>

        <!-- إحصائيات -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6 border-r-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">الأنشطة المحضورة</p>
                        <h3 class="text-3xl font-bold text-green-600">{{ $totalPresent }}</h3>
                    </div>
                    <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6 border-r-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">إجمالي الأنشطة</p>
                        <h3 class="text-3xl font-bold text-blue-600">{{ $totalActivities }}</h3>
                    </div>
                    <i class="fas fa-calendar text-blue-500 text-3xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6 border-r-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">نسبة الحضور</p>
                        <h3 class="text-3xl font-bold text-purple-600">{{ round($attendanceRate, 1) }}%</h3>
                    </div>
                    <i class="fas fa-chart-line text-purple-500 text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- جدول السجل -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">سجل الحضور التفصيلي</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">النشاط</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">وقت الحضور</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">النقاط</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($attendanceRecords as $record)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-900">{{ $record->activity->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $record->activity->location }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $record->activity->date->format('Y/m/d') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $record->check_in_time ? $record->check_in_time->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($record->status === 'present')
                                        <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full font-bold">
                                            <i class="fas fa-check ml-1"></i>حاضر
                                        </span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs px-3 py-1 rounded-full font-bold">
                                            <i class="fas fa-times ml-1"></i>غائب
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-blue-600">
                                    +{{ $record->points_earned }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-3 block"></i>
                                    لا توجد سجلات حضور حتى الآن
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($attendanceRecords->hasPages())
                <div class="p-6 border-t border-gray-200">
                    {{ $attendanceRecords->links() }}
                </div>
            @endif
        </div>
    </div>

</body>
</html>