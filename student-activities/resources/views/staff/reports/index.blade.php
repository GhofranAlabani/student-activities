<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقارير - لوحة المشرف</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .bg-navy { background-color: #0a1929; }
        .bg-navy-light { background-color: #112240; }
        .text-gold { color: #d4a017; }
        .bg-gold { background-color: #d4a017; }
        .bg-gold:hover { background-color: #b8860b; }
        .sidebar-link { transition: all 0.3s ease; }
        .sidebar-link:hover { background-color: rgba(212, 160, 23, 0.1); transform: translateX(-5px); }
        .sidebar-link.active { background-color: rgba(212, 160, 23, 0.2); border-right: 3px solid #d4a017; }
    </style>
</head>
<body class="bg-[#f5f0e8] min-h-screen">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-72 bg-navy text-white fixed right-0 top-0 h-full shadow-2xl z-50 overflow-y-auto">
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gold rounded-xl flex items-center justify-center text-navy font-black text-xl shadow-lg">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gold">لوحة المشرف</h2>
                        <p class="text-xs text-gray-400">{{ auth()->user()->name }}</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-2">
                <a href="{{ route('staff.dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-home text-gold w-5"></i>
                    <span class="font-bold">الرئيسية</span>
                </a>
                <a href="{{ route('staff.activities.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-calendar-alt text-gold w-5"></i>
                    <span class="font-bold">أنشطتي</span>
                </a>
                <a href="{{ route('staff.activities.create') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-plus-circle text-gold w-5"></i>
                    <span class="font-bold">إضافة نشاط</span>
                </a>
                <a href="{{ route('staff.students.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-users text-gold w-5"></i>
                    <span class="font-bold">الطلاب المسجلين</span>
                </a>
                <a href="{{ route('staff.reports.index') }}" class="sidebar-link active flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-chart-bar text-gold w-5"></i>
                    <span class="font-bold">التقارير</span>
                </a>
                <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-bullhorn text-gold w-5"></i>
                    <span class="font-bold">الإعلانات</span>
                </a>
                <div class="border-t border-white/10 my-4"></div>
               <a href="{{ route('staff.announcements.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
    <i class="fas fa-bullhorn text-gold w-5"></i>
    <span class="font-bold">الإعلانات</span>
</a>
            </nav>

            <div class="absolute bottom-0 right-0 left-0 p-4 border-t border-white/10 bg-navy-light">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-400 hover:bg-red-500/10 transition">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="font-bold">تسجيل الخروج</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 mr-72">
            
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 sticky top-0 z-40">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-black text-navy">التقارير والإحصائيات</h1>
                        <p class="text-sm text-gray-500 mt-1">تحليل شامل لأدائك وأنشطتك</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-left">
                            <p class="font-bold text-navy">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">مشرف أنشطة</p>
                        </div>
                        <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center text-navy font-bold shadow-lg">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-8">
                
                <!-- فلاتر التاريخ -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                    <form method="GET" action="{{ route('staff.reports.index') }}" class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-navy font-bold mb-2 text-sm">من تاريخ</label>
                            <input type="date" name="from_date" value="{{ $fromDate }}" 
                                   class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold">
                        </div>
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-navy font-bold mb-2 text-sm">إلى تاريخ</label>
                            <input type="date" name="to_date" value="{{ $toDate }}" 
                                   class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-gold text-navy px-6 py-2 rounded-xl font-bold hover:bg-yellow-600 transition">
                                <i class="fas fa-filter ml-1"></i> تطبيق
                            </button>
                            <a href="{{ route('staff.reports.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-xl font-bold hover:bg-gray-300 transition">
                                <i class="fas fa-redo ml-1"></i> إعادة
                            </a>
                        </div>
                    </form>
                </div>

                <!-- KPIs -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border-r-4 border-gold">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">إجمالي الأنشطة</p>
                                <h3 class="text-3xl font-black text-navy mt-1">{{ $totalActivities }}</h3>
                            </div>
                            <div class="w-14 h-14 bg-gold/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar text-gold text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6 border-r-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">إجمالي التسجيلات</p>
                                <h3 class="text-3xl font-black text-navy mt-1">{{ $totalRegistrations }}</h3>
                            </div>
                            <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-clipboard-list text-blue-500 text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6 border-r-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">معدل القبول</p>
                                <h3 class="text-3xl font-black text-navy mt-1">
                                    @if($totalRegistrations > 0)
                                        {{ number_format(($totalApproved / $totalRegistrations) * 100, 1) }}%
                                    @else
                                        0%
                                    @endif
                                </h3>
                            </div>
                            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6 border-r-4 border-purple-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">متوسط التقييم</p>
                                <h3 class="text-3xl font-black text-navy mt-1">{{ number_format($avgRating, 1) }} ⭐</h3>
                            </div>
                            <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-star text-purple-500 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الرسوم البيانية -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    
                    <!-- الأنشطة حسب النوع -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-black text-navy mb-6 flex items-center gap-2">
                            <i class="fas fa-chart-pie text-gold"></i>
                            الأنشطة حسب النوع
                        </h3>
                        <div class="relative" style="height: 300px;">
                            <canvas id="typeChart"></canvas>
                        </div>
                    </div>

                    <!-- التسجيلات الشهرية -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-black text-navy mb-6 flex items-center gap-2">
                            <i class="fas fa-chart-line text-gold"></i>
                            التسجيلات الشهرية
                        </h3>
                        <div class="relative" style="height: 300px;">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>

                    <!-- حالة التسجيلات -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-black text-navy mb-6 flex items-center gap-2">
                            <i class="fas fa-chart-bar text-gold"></i>
                            حالة التسجيلات
                        </h3>
                        <div class="relative" style="height: 300px;">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>

                    <!-- أفضل الأنشطة -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-black text-navy mb-6 flex items-center gap-2">
                            <i class="fas fa-trophy text-gold"></i>
                            أفضل 5 أنشطة
                        </h3>
                        <div class="space-y-3">
                            @forelse($topActivities as $index => $activity)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                                    <div class="w-10 h-10 bg-gold rounded-full flex items-center justify-center text-navy font-black">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-navy">{{ $activity['title'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $activity['date'] }}</p>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-black text-gold">{{ $activity['registrations'] }}</p>
                                        <p class="text-xs text-gray-500">مسجل</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-8">لا توجد بيانات</p>
                            @endforelse
                        </div>
                    </div>
                </div>

              <!-- أزرار التصدير -->
<div class="bg-white rounded-2xl shadow-lg p-6">
    <h3 class="text-xl font-black text-navy mb-6 flex items-center gap-2">
        <i class="fas fa-download text-gold"></i>
        تصدير التقارير
    </h3>
    <div class="flex gap-4 flex-wrap">
        <a href="{{ route('staff.reports.export.pdf', ['from_date' => $fromDate, 'to_date' => $toDate]) }}" 
           class="bg-red-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-red-600 transition">
            <i class="fas fa-file-pdf ml-2"></i> تصدير PDF
        </a>
        <a href="{{ route('staff.reports.export.excel', ['from_date' => $fromDate, 'to_date' => $toDate]) }}" 
           class="bg-green-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-green-600 transition">
            <i class="fas fa-file-excel ml-2"></i> تصدير Excel
        </a>
        <button onclick="window.print()" class="bg-blue-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-600 transition">
            <i class="fas fa-print ml-2"></i> طباعة
        </button>
    </div>
</div>

    <!-- Chart.js Scripts -->
    <script>
        // ألوان موحدة
        const colors = ['#d4a017', '#0a1929', '#112240', '#3b82f6', '#10b981', '#ef4444', '#8b5cf6'];
        
        // 1. رسم بياني دائري - الأنشطة حسب النوع
        const typeCtx = document.getElementById('typeChart').getContext('2d');
        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($activitiesByType->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($activitiesByType->pluck('count')) !!},
                    backgroundColor: colors,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { family: 'Cairo' } } }
                }
            }
        });

        // 2. رسم بياني خطي - التسجيلات الشهرية
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: {!!