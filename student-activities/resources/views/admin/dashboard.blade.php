@extends('layouts.admin')

@section('content')

<!-- Header (Top Bar) -->
<header class="bg-[#0f172a] rounded-2xl p-4 mb-8 shadow-lg flex justify-between items-center text-white relative overflow-hidden">
    
    <!-- اليمين: الترحيب -->
    <div class="flex items-center gap-3 z-10">
        <i class="fas fa-graduation-cap text-amber-500 text-3xl"></i>
        <div>
            <h1 class="text-2xl font-bold text-white mb-1">مرحباً بك، المشرف العام</h1>
            <p class="text-slate-400 text-sm">لوحة تحكم إدارة الأنشطة الطلابية</p>
        </div>
    </div>

    <!-- اليسار: التاريخ + زر الوضع الليلي (تحته) -->
    <div class="flex flex-col items-end gap-2 z-10">
        
        <!-- 1. التاريخ -->
        <div class="bg-slate-800 px-4 py-2 rounded-xl border border-slate-700 flex items-center gap-2">
            <span class="font-semibold text-sm">{{ now()->format('d/m/Y') }}</span>
            <i class="far fa-calendar-alt text-amber-500"></i>
        </div>

        <!-- 2. زر الوضع الليلي (رمز صغير تحت التاريخ) -->
        <button onclick="toggleDarkMode()" 
                class="w-9 h-9 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-400 hover:text-amber-500 hover:border-amber-500 transition-all shadow-md"
                title="تبديل الوضع الليلي">
            <i class="fas fa-moon text-xs" id="darkModeIcon"></i>
        </button>

    </div>

</header>

<!-- Main Content -->
<main class="p-6 md:p-8">

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Card 1: مجموع الأنشطة -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow group dark:bg-slate-800 dark:border-slate-700">
            <div class="text-right">
                <p class="text-sm text-slate-500 font-bold mb-1 dark:text-slate-400">مجموع الأنشطة</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white">{{ $totalActivities ?? 0 }}</h3>
            </div>
            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-colors dark:bg-blue-900/30">
                <i class="fas fa-calendar-alt text-2xl"></i>
            </div>
        </div>

        <!-- Card 2: إجمالي التسجيلات -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow group dark:bg-slate-800 dark:border-slate-700">
            <div class="text-right">
                <p class="text-sm text-slate-500 font-bold mb-1 dark:text-slate-400">إجمالي التسجيلات</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white">{{ $totalRegistrations ?? 0 }}</h3>
            </div>
            <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 group-hover:bg-amber-500 group-hover:text-white transition-colors dark:bg-amber-900/30">
                <i class="fas fa-clipboard-list text-2xl"></i>
            </div>
        </div>

        <!-- Card 3: عدد الطلاب -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow group dark:bg-slate-800 dark:border-slate-700">
            <div class="text-right">
                <p class="text-sm text-slate-500 font-bold mb-1 dark:text-slate-400">عدد الطلاب</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white">{{ $totalStudents ?? 0 }}</h3>
            </div>
            <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-colors dark:bg-emerald-900/30">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>

        <!-- Card 4: الاستبيانات النشطة -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow group dark:bg-slate-800 dark:border-slate-700">
            <div class="text-right">
                <p class="text-sm text-slate-500 font-bold mb-1 dark:text-slate-400">الاستبيانات النشطة</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white">{{ \App\Models\SurveyQuestion::count() }}</h3>
            </div>
            <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-500 group-hover:bg-purple-500 group-hover:text-white transition-colors dark:bg-purple-900/30">
                <i class="fas fa-poll text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Line Chart -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 dark:bg-slate-800 dark:border-slate-700">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2 dark:text-white">
                <i class="fas fa-chart-line text-blue-500"></i>
                معدل التسجيلات الشهري
            </h3>
            <div class="relative h-64 w-full">
                <canvas id="registrationChart"></canvas>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 dark:bg-slate-800 dark:border-slate-700">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2 dark:text-white">
                <i class="fas fa-chart-pie text-purple-500"></i>
                توزيع الأنشطة
            </h3>
            <div class="relative h-64 w-full flex justify-center">
                <canvas id="activityTypeChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions (الإجراءات السريعة) -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 md:p-8 mb-8 dark:bg-slate-800 dark:border-slate-700">
        <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2 dark:text-white">
            <i class="fas fa-bolt text-amber-500"></i>
            إجراءات سريعة
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Action 1: إضافة نشاط جديد -->
            <a href="{{ route('activities.create') }}" 
               class="flex flex-col items-center justify-center p-8 bg-white rounded-2xl border-2 border-slate-200 hover:border-blue-500 hover:bg-blue-50 text-slate-600 hover:text-blue-600 transition-all gap-4 group shadow-sm h-40 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-blue-900/30">
                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center text-blue-500 group-hover:bg-white group-hover:text-blue-500 transition-all shadow-sm dark:bg-blue-900/30">
                    <i class="fas fa-plus-circle text-2xl"></i>
                </div>
                <span class="text-base font-bold">إضافة نشاط جديد</span>
            </a>

            <!-- Action 2: إدارة الطلاب -->
            <a href="{{ route('admin.students') }}" 
               class="flex flex-col items-center justify-center p-8 bg-white rounded-2xl border-2 border-slate-200 hover:border-emerald-500 hover:bg-emerald-50 text-slate-600 hover:text-emerald-600 transition-all gap-4 group shadow-sm h-40 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-emerald-900/30">
                <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500 group-hover:bg-white group-hover:text-emerald-500 transition-all shadow-sm dark:bg-emerald-900/30">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <span class="text-base font-bold">إدارة الطلاب</span>
            </a>

            <!-- Action 3: الاستبيانات -->
            <a href="{{ route('admin.survey-questions.index') }}" 
               class="flex flex-col items-center justify-center p-8 bg-white rounded-2xl border-2 border-slate-200 hover:border-purple-500 hover:bg-purple-50 text-slate-600 hover:text-purple-600 transition-all gap-4 group shadow-sm h-40 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-purple-900/30">
                <div class="w-16 h-16 bg-purple-50 rounded-full flex items-center justify-center text-purple-500 group-hover:bg-white group-hover:text-purple-500 transition-all shadow-sm dark:bg-purple-900/30">
                    <i class="fas fa-poll text-2xl"></i>
                </div>
                <span class="text-base font-bold">الاستبيانات</span>
            </a>

            <!-- Action 4: الإعلانات -->
            <a href="{{ route('admin.announcements') }}" 
               class="flex flex-col items-center justify-center p-8 bg-white rounded-2xl border-2 border-slate-200 hover:border-red-500 hover:bg-red-50 text-slate-600 hover:text-red-600 transition-all gap-4 group shadow-sm h-40 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-red-900/30">
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center text-red-500 group-hover:bg-white group-hover:text-red-500 transition-all shadow-sm dark:bg-red-900/30">
                    <i class="fas fa-bullhorn text-2xl"></i>
                </div>
                <span class="text-base font-bold">الإعلانات</span>
            </a>

            <!-- Action 5: إدارة التسجيلات -->
            <a href="{{ route('admin.all-registrations') }}" 
               class="flex flex-col items-center justify-center p-8 bg-white rounded-2xl border-2 border-slate-200 hover:border-amber-500 hover:bg-amber-50 text-slate-600 hover:text-amber-600 transition-all gap-4 group shadow-sm h-40 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-amber-900/30">
                <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center text-amber-500 group-hover:bg-white group-hover:text-amber-500 transition-all shadow-sm dark:bg-amber-900/30">
                    <i class="fas fa-clipboard-list text-2xl"></i>
                </div>
                <span class="text-base font-bold">إدارة التسجيلات</span>
            </a>

            <!-- Action 6: تصدير تقرير -->
            <a href="#" 
               class="flex flex-col items-center justify-center p-8 bg-white rounded-2xl border-2 border-slate-200 hover:border-slate-500 hover:bg-slate-50 text-slate-600 hover:text-slate-700 transition-all gap-4 group shadow-sm h-40 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-600">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-500 group-hover:bg-white group-hover:text-slate-600 transition-all shadow-sm dark:bg-slate-600">
                    <i class="fas fa-file-export text-2xl"></i>
                </div>
                <span class="text-base font-bold">تصدير تقرير</span>
            </a>

        </div>
    </div>

    <!-- آخر النشاطات (Recent Activities) -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 md:p-8 mt-8 dark:bg-slate-800 dark:border-slate-700">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2 dark:text-white">
                <i class="fas fa-history text-amber-500"></i>
                آخر النشاطات
            </h2>
            <a href="{{ route('admin.all-registrations') }}" class="text-sm text-amber-600 hover:text-amber-700 font-bold flex items-center gap-1 dark:text-amber-400">
                عرض الكل
                <i class="fas fa-arrow-left mr-1"></i>
            </a>
        </div>

        <div class="space-y-4">
            @forelse($recentActivities as $activity)
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100 hover:border-amber-300 hover:shadow-md transition-all dark:bg-slate-700 dark:border-slate-600">
                    <!-- أيقونة النشاط -->
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 shrink-0 dark:bg-blue-900/30">
                        <i class="fas fa-user-check text-lg"></i>
                    </div>

                    <!-- معلومات النشاط -->
                    <div class="flex-1">
                        <p class="text-slate-800 font-semibold text-sm dark:text-white">
                            سجل الطالب <span class="text-amber-600 font-bold dark:text-amber-400">{{ $activity->student_name }}</span> 
                            في نشاط <span class="text-blue-600 font-bold dark:text-blue-400">"{{ $activity->activity_title }}"</span>
                        </p>
                        <p class="text-slate-500 text-xs mt-1 flex items-center gap-1 dark:text-slate-400">
                            <i class="far fa-clock"></i>
                            {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                        </p>
                    </div>

                    <!-- حالة التسجيل -->
                    <div class="hidden md:block">
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full dark:bg-green-900/30 dark:text-green-400">
                            <i class="fas fa-check-circle ml-1"></i>
                            مسجل بنجاح
                        </span>
                    </div>
                </div>
            @empty
                <!-- رسالة في حال عدم وجود نشاطات -->
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 dark:bg-slate-700">
                        <i class="fas fa-inbox text-4xl text-slate-400"></i>
                    </div>
                    <p class="text-slate-500 font-semibold dark:text-slate-400">لا توجد نشاطات حديثة</p>
                    <p class="text-slate-400 text-sm mt-1 dark:text-slate-500">ستظهر هنا آخر عمليات التسجيل في الأنشطة</p>
                </div>
            @endforelse
        </div>
    </div>

</main>

<!-- Charts Scripts + Dark Mode -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@push('scripts')
<script>
    // ============================================
    // 🌙 دالة تبديل الوضع الليلي
    // ============================================
    function toggleDarkMode() {
        const html = document.documentElement;
        const icon = document.getElementById('darkModeIcon');
        
        if (html.classList.contains('dark')) {
            // تحويل للوضع الفاتح
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            icon.className = 'fas fa-moon text-xs';
        } else {
            // تحويل للوضع الداكن
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            icon.className = 'fas fa-sun text-xs';
        }
        
        // إعادة رسم الرسوم البيانية بألوان جديدة
        updateChartsTheme();
    }

    // تطبيق الإعداد المحفوظ عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        const html = document.documentElement;
        const icon = document.getElementById('darkModeIcon');
        const savedTheme = localStorage.getItem('theme');
        
        if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
            icon.className = 'fas fa-sun text-xs';
        }
        
        // تهيئة الرسوم البيانية
        initCharts();
    });

    // ============================================
    // 📊 تهيئة الرسوم البيانية
    // ============================================
    function getChartColors() {
        const isDark = document.documentElement.classList.contains('dark');
        return {
            textColor: isDark ? '#94a3b8' : '#64748b',
            gridColor: isDark ? '#334155' : '#e2e8f0',
            borderColor: '#f59e0b',
            backgroundColor: isDark ? 'rgba(251, 191, 36, 0.2)' : 'rgba(251, 191, 36, 0.1)'
        };
    }

    let lineChart, pieChart;

    function initCharts() {
        const colors = getChartColors();
        const months = @json($months ?? []);
        const regCounts = @json($counts ?? []);
        const typeLabels = @json($typeLabels ?? []);
        const typeData = @json($typeData ?? []);

        // Line Chart
        const ctxLine = document.getElementById('registrationChart');
        if (ctxLine) {
            lineChart = new Chart(ctxLine.getContext('2d'), {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'التسجيلات',
                        data: regCounts,
                        borderColor: colors.borderColor,
                        backgroundColor: colors.backgroundColor,
                        borderWidth: 2, tension: 0.4, fill: true,
                        pointBackgroundColor: '#fff', pointBorderColor: colors.borderColor
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            grid: { color: colors.gridColor },
                            ticks: { color: colors.textColor }
                        },
                        x: {
                            grid: { color: colors.gridColor },
                            ticks: { color: colors.textColor }
                        }
                    }
                }
            });
        }

        // Pie Chart
        const ctxPie = document.getElementById('activityTypeChart');
        if (ctxPie) {
            pieChart = new Chart(ctxPie.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: typeLabels,
                    datasets: [{
                        data: typeData,
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#64748b'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { 
                        legend: { 
                            position: 'bottom', 
                            labels: { 
                                font: { family: 'Cairo' },
                                color: colors.textColor
                            } 
                        } 
                    }
                }
            });
        }
    }

    // تحديث ألوان الرسوم عند تغيير الوضع
    function updateChartsTheme() {
        const colors = getChartColors();
        
        if (lineChart) {
            lineChart.options.scales.y.grid.color = colors.gridColor;
            lineChart.options.scales.y.ticks.color = colors.textColor;
            lineChart.options.scales.x.grid.color = colors.gridColor;
            lineChart.options.scales.x.ticks.color = colors.textColor;
            lineChart.data.datasets[0].borderColor = colors.borderColor;
            lineChart.data.datasets[0].backgroundColor = colors.backgroundColor;
            lineChart.update();
        }
        
        if (pieChart) {
            pieChart.options.plugins.legend.labels.color = colors.textColor;
            pieChart.update();
        }
    }
</script>
@endpush

@endsection