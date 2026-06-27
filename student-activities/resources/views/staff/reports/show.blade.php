<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير النشاط - {{ $activity->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .bg-navy { background-color: #0a1929; }
        .text-gold { color: #d4a017; }
        .bg-gold { background-color: #d4a017; }
        .bg-gold:hover { background-color: #b8860b; }
    </style>
</head>
<body class="bg-[#f5f0e8] min-h-screen">

    <!-- Navbar -->
    <nav class="bg-navy text-white shadow-lg">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gold rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-shield text-navy"></i>
                </div>
                <div>
                    <h1 class="font-bold text-gold">لوحة المشرف</h1>
                    <p class="text-xs text-gray-300">{{ auth()->user()->name }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('staff.activities.show', $activity->id) }}" class="text-gray-300 hover:text-gold text-sm">
                    <i class="fas fa-arrow-right ml-1"></i> رجوع للتفاصيل
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-8 max-w-5xl">

        <!-- عنوان التقرير -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-black text-navy flex items-center gap-2">
                    <i class="fas fa-chart-bar text-gold"></i>
                    تقرير النشاط
                </h2>
                <p class="text-gray-500 mt-1">{{ $activity->title }}</p>
            </div>
            <a href="{{ route('staff.report.export', $activity->id) }}" class="bg-navy text-white px-4 py-2 rounded-lg hover:bg-gold hover:text-navy transition text-sm font-bold">
                <i class="fas fa-download ml-1"></i> تصدير التقرير
            </a>
        </div>

        <!-- إحصائيات التسجيل -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-lg border-r-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">إجمالي المسجلين</p>
                        <h3 class="text-3xl font-black text-navy">{{ $totalRegistrations }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-blue-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border-r-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">المقبولون</p>
                        <h3 class="text-3xl font-black text-navy">{{ $approvedRegistrations }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border-r-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">بانتظار الموافقة</p>
                        <h3 class="text-3xl font-black text-navy">{{ $pendingRegistrations }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- التقييمات -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-navy mb-4 flex items-center gap-2">
                <i class="fas fa-star text-gold"></i>
                التقييمات
            </h3>
            
            @if($ratingsCount > 0)
                <div class="flex items-center gap-6">
                    <div class="text-center">
                        <p class="text-5xl font-black text-gold">{{ number_format($avgRating, 1) }}</p>
                        <p class="text-gray-500 text-sm mt-1">من 5 نجوم</p>
                    </div>
                    <div class="flex-1">
                        <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
                            <div class="bg-gold h-4 rounded-full" style="width: {{ ($avgRating / 5) * 100 }}%"></div>
                        </div>
                        <p class="text-sm text-gray-600">بناءً على {{ $ratingsCount }} تقييم</p>
                    </div>
                </div>
            @else
                <div class="text-center py-6">
                    <i class="fas fa-star-half-alt text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500">لا توجد تقييمات لهذا النشاط بعد</p>
                </div>
            @endif
        </div>

    </div>

</body>
</html>