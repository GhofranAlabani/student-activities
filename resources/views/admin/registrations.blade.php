<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الطلاب المسجلين - {{ $activity->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { font-family: 'Cairo', sans-serif; }</style>
</head>
<body class="bg-gray-50">

    <div class="container mx-auto px-4 py-8">
        <!-- رأس الصفحة -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">
                        <i class="fas fa-users ml-2 text-indigo-600"></i>
                        الطلاب المسجلين
                    </h1>
                    <p class="text-gray-600">النشاط: <span class="font-bold text-indigo-600">{{ $activity->title }}</span></p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-right ml-2"></i> رجوع
                </a>
            </div>
        </div>

        <!-- معلومات النشاط -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6 mb-6 text-white">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-indigo-100 text-sm">عدد المسجلين</p>
                    <p class="text-3xl font-bold">{{ $students->count() }} / {{ $activity->max_participants }}</p>
                </div>
                <div>
                    <p class="text-indigo-100 text-sm">التاريخ</p>
                    <p class="text-xl font-bold">{{ \Carbon\Carbon::parse($activity->date)->format('Y/m/d') }}</p>
                </div>
                <div>
                    <p class="text-indigo-100 text-sm">الموقع</p>
                    <p class="text-xl font-bold">{{ $activity->location }}</p>
                </div>
            </div>
        </div>

        <!-- جدول الطلاب -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            @if($students->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-right font-bold text-gray-700">#</th>
                                <th class="px-6 py-4 text-right font-bold text-gray-700">اسم الطالب</th>
                                <th class="px-6 py-4 text-right font-bold text-gray-700">البريد الإلكتروني</th>
                                <th class="px-6 py-4 text-right font-bold text-gray-700">تاريخ التسجيل</th>
                                <th class="px-6 py-4 text-right font-bold text-gray-700">الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($students as $index => $student)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $student->name }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $student->email }}</td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $student->pivot->created_at->format('Y/m/d H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-bold">
                                            مسجل
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="fas fa-user-times text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-xl">لا يوجد طلاب مسجلين في هذا النشاط بعد</p>
                </div>
            @endif
        </div>
    </div>

</body>
</html>