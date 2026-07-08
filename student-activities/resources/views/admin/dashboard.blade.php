@extends('layouts.admin')

@section('content')

<!-- الشريط العلوي المدمج (Dark Header) -->
<div class="bg-[#0f172a] rounded-2xl p-6 mb-8 shadow-lg flex justify-between items-center text-white">
    
    <!-- اليمين: رسالة الترحيب (نقلنا المحتوى الأخضر لهنا) -->
    <div class="flex items-center gap-3">
        <i class="fas fa-graduation-cap text-amber-500 text-3xl"></i>
        <div>
            <h1 class="text-2xl font-bold text-white mb-1">مرحباً بك، المشرف العام</h1>
            <p class="text-slate-400 text-sm">لوحة تحكم إدارة الأنشطة الطلابية</p>
        </div>
    </div>

    <!-- اليسار: التاريخ (نقلناه لجهة ثانية) -->
    <div class="bg-slate-800 px-4 py-2 rounded-xl border border-slate-700 flex items-center gap-2">
        <span class="font-semibold">{{ now()->format('d/m/Y') }}</span>
        <i class="far fa-calendar-alt text-amber-500"></i>
    </div>
</div>

</header>

    <!-- Main Content -->
    <main class="p-6 md:p-8">

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <!-- Card 1 -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow group">
                <div class="text-right">
                    <p class="text-sm text-slate-500 font-bold mb-1">مجموع الأنشطة</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $totalActivities ?? 0 }}</h3>
                </div>
                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow group">
                <div class="text-right">
                    <p class="text-sm text-slate-500 font-bold mb-1">إجمالي التسجيلات</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $totalRegistrations ?? 0 }}</h3>
                </div>
                <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                    <i class="fas fa-clipboard-list text-2xl"></i>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow group">
                <div class="text-right">
                    <p class="text-sm text-slate-500 font-bold mb-1">عدد الطلاب</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $totalStudents ?? 0 }}</h3>
                </div>
                <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow group">
                <div class="text-right">
                    <p class="text-sm text-slate-500 font-bold mb-1">الاستبيانات النشطة</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ \App\Models\SurveyQuestion::count() }}</h3>
                </div>
                <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-500 group-hover:bg-purple-500 group-hover:text-white transition-colors">
                    <i class="fas fa-poll text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Line Chart -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-line text-blue-500"></i>
                    معدل التسجيلات الشهري
                </h3>
                <div class="relative h-64 w-full">
                    <canvas id="registrationChart"></canvas>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-pie text-purple-500"></i>
                    توزيع الأنشطة
                </h3>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="activityTypeChart"></canvas>
                </div>
            </div>
        </div>

       <!-- Quick Actions (الإجراءات السريعة) -->
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 md:p-8 mb-8">
    <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
        <i class="fas fa-bolt text-amber-500"></i>
        إجراءات سريعة
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Action 1: إضافة نشاط جديد -->
        <a href="{{ route('activities.create') }}" 
           class="flex flex-col items-center justify-center p-8 bg-white rounded-2xl border-2 border-slate-200 hover:border-blue-500 hover:bg-blue-50 text-slate-600 hover:text-blue-600 transition-all gap-4 group shadow-sm h-40">
            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center text-blue-500 group-hover:bg-white group-hover:text-blue-500 transition-all shadow-sm">
                <i class="fas fa-plus-circle text-2xl"></i>
            </div>
            <span class="text-base font-bold">إضافة نشاط جديد</span>
        </a>

        <!-- Action 2: إدارة الطلاب -->
        <a href="{{ route('admin.students') }}" 
           class="flex flex-col items-center justify-center p-8 bg-white rounded-2xl border-2 border-slate-200 hover:border-emerald-500 hover:bg-emerald-50 text-slate-600 hover:text-emerald-600 transition-all gap-4 group shadow-sm h-40">
            <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500 group-hover:bg-white group-hover:text-emerald-500 transition-all shadow-sm">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <span class="text-base font-bold">إدارة الطلاب</span>
        </a>

        <!-- Action 3: الاستبيانات -->
        <a href="{{ route('admin.survey-questions.index') }}" 
           class="flex flex-col items-center justify-center p-8 bg-white rounded-2xl border-2 border-slate-200 hover:border-purple-500 hover:bg-purple-50 text-slate-600 hover:text-purple-600 transition-all gap-4 group shadow-sm h-40">
            <div class="w-16 h-16 bg-purple-50 rounded-full flex items-center justify-center text-purple-500 group-hover:bg-white group-hover:text-purple-500 transition-all shadow-sm">
                <i class="fas fa-poll text-2xl"></i>
            </div>
            <span class="text-base font-bold">الاستبيانات</span>
        </a>

        <!-- Action 4: الإعلانات -->
        <a href="{{ route('admin.announcements') }}" 
           class="flex flex-col items-center justify-center p-8 bg-white rounded-2xl border-2 border-slate-200 hover:border-red-500 hover:bg-red-50 text-slate-600 hover:text-red-600 transition-all gap-4 group shadow-sm h-40">
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center text-red-500 group-hover:bg-white group-hover:text-red-500 transition-all shadow-sm">
                <i class="fas fa-bullhorn text-2xl"></i>
            </div>
            <span class="text-base font-bold">الإعلانات</span>
        </a>

        <!-- Action 5: إدارة التسجيلات -->
        <a href="{{ route('admin.all-registrations') }}" 
           class="flex flex-col items-center justify-center p-8 bg-white rounded-2xl border-2 border-slate-200 hover:border-amber-500 hover:bg-amber-50 text-slate-600 hover:text-amber-600 transition-all gap-4 group shadow-sm h-40">
            <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center text-amber-500 group-hover:bg-white group-hover:text-amber-500 transition-all shadow-sm">
                <i class="fas fa-clipboard-list text-2xl"></i>
            </div>
            <span class="text-base font-bold">إدارة التسجيلات</span>
        </a>

        <!-- Action 6: تصدير تقرير -->
        <a href="#" 
           class="flex flex-col items-center justify-center p-8 bg-white rounded-2xl border-2 border-slate-200 hover:border-slate-500 hover:bg-slate-50 text-slate-600 hover:text-slate-700 transition-all gap-4 group shadow-sm h-40">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-500 group-hover:bg-white group-hover:text-slate-600 transition-all shadow-sm">
                <i class="fas fa-file-export text-2xl"></i>
            </div>
            <span class="text-base font-bold">تصدير تقرير</span>
        </a>

    </div>
</div>
<!-- آخر النشاطات (Recent Activities) -->
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 md:p-8 mt-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
            <i class="fas fa-history text-amber-500"></i>
            آخر النشاطات
        </h2>
        <a href="{{ route('admin.all-registrations') }}" class="text-sm text-amber-600 hover:text-amber-700 font-bold flex items-center gap-1">
            عرض الكل
            <i class="fas fa-arrow-left mr-1"></i>
        </a>
    </div>

    <div class="space-y-4">
        @forelse($recentActivities as $activity)
            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100 hover:border-amber-300 hover:shadow-md transition-all">
                <!-- أيقونة النشاط -->
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 shrink-0">
                    <i class="fas fa-user-check text-lg"></i>
                </div>

                <!-- معلومات النشاط -->
                <div class="flex-1">
                    <p class="text-slate-800 font-semibold text-sm">
                        سجل الطالب <span class="text-amber-600 font-bold">{{ $activity->student_name }}</span> 
                        في نشاط <span class="text-blue-600 font-bold">"{{ $activity->activity_title }}"</span>
                    </p>
                    <p class="text-slate-500 text-xs mt-1 flex items-center gap-1">
                        <i class="far fa-clock"></i>
                        {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                    </p>
                </div>

                <!-- حالة التسجيل -->
                <div class="hidden md:block">
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                        <i class="fas fa-check-circle ml-1"></i>
                        مسجل بنجاح
                    </span>
                </div>
            </div>
        @empty
            <!-- رسالة في حال عدم وجود نشاطات -->
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-4xl text-slate-400"></i>
                </div>
                <p class="text-slate-500 font-semibold">لا توجد نشاطات حديثة</p>
                <p class="text-slate-400 text-sm mt-1">ستظهر هنا آخر عمليات التسجيل في الأنشطة</p>
            </div>
        @endforelse
    </div>
</div>

    <!-- Charts Scripts Only -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const months = @json($months ?? []);
        const regCounts = @json($counts ?? []);
        const typeLabels = @json($typeLabels ?? []);
        const typeData = @json($typeData ?? []);

        // Line Chart
        const ctxLine = document.getElementById('registrationChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'التسجيلات',
                    data: regCounts,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(251, 191, 36, 0.1)',
                    borderWidth: 2, tension: 0.4, fill: true,
                    pointBackgroundColor: '#fff', pointBorderColor: '#f59e0b'
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }
            }
        });

        // Pie Chart
        const ctxPie = document.getElementById('activityTypeChart').getContext('2d');
        new Chart(ctxPie, {
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
                plugins: { legend: { position: 'bottom', labels: { font: { family: 'Cairo' } } } }
            }
        });
    </script>
</div>
@endsection