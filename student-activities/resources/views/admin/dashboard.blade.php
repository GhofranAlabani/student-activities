@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="min-h-screen bg-[#f8f6f0]" style="font-family: 'Cairo', sans-serif;">
    
    <!-- Header (Top Bar) - بدون إشعارات -->
    <!-- Header (Top Bar) - بدون قائمة منسدلة -->
<!-- Header (Top Bar) - التاريخ والمشرف في اليسار -->
<header class="bg-[#0f172a] text-white p-4 flex justify-between items-center shadow-lg sticky top-0 z-50">
    
    <!-- اليسار: التاريخ والمستخدم -->
    <div class="flex items-center gap-6">
        
        <!-- التاريخ -->
        <div class="hidden md:block bg-slate-800 px-3 py-1.5 rounded-lg text-sm text-slate-300 border border-slate-700">
            <i class="far fa-calendar-alt ml-2 text-amber-500"></i>
            {{ now()->format('Y/m/d') }}
        </div>

        <!-- 👤 اسم المستخدم والصورة -->
        <div class="flex items-center gap-3 pl-2 border-l border-slate-700">
            <div class="text-right hidden md:block">
                <p class="text-sm font-bold text-white">المشرف العام</p>
                <p class="text-xs text-slate-400">مدير النظام</p>
            </div>
            <img src="https://ui-avatars.com/api/?name=Admin&background=d4a017&color=fff" 
                 class="w-10 h-10 rounded-full border-2 border-slate-700 shadow-sm">
        </div>

    </div>

    <!-- اليمين: الشعار/العنوان -->
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-[#0f172a] font-bold shadow-lg shadow-amber-500/20">
            <i class="fas fa-user-shield"></i>
        </div>
        <div>
            <h1 class="text-lg font-bold text-amber-500 leading-none">لوحة الإدارة</h1>
            <p class="text-xs text-slate-400">نظام الأنشطة الطلابية</p>
        </div>
    </div>

</header>

    <!-- Main Content -->
    <main class="p-6 md:p-8">
        
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800 mb-1">
                مرحباً بك، المشرف العام
                <i class="fas fa-graduation-cap text-amber-500 text-2xl mr-2"></i>
            </h1>
            <p class="text-slate-500">لوحة تحكم إدارة الأنشطة الطلابية</p>
        </div>

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

        <!-- Quick Actions -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 md:p-8 mb-8">
            <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="fas fa-bolt text-amber-500"></i>
                إجراءات سريعة
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <a href="{{ route('activities.create') }}" class="flex flex-col items-center justify-center p-5 bg-slate-50 rounded-2xl hover:bg-blue-50 text-slate-600 hover:text-blue-600 transition-colors gap-3 group border border-transparent hover:border-blue-100">
                    <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                        <i class="fas fa-plus-circle text-xl"></i>
                    </div>
                    <span class="text-sm font-bold">إضافة نشاط جديد</span>
                </a>

                <a href="{{ route('admin.students') }}" class="flex flex-col items-center justify-center p-5 bg-slate-50 rounded-2xl hover:bg-green-50 text-slate-600 hover:text-green-600 transition-colors gap-3 group border border-transparent hover:border-green-100">
                    <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-green-500 group-hover:scale-110 transition-transform">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <span class="text-sm font-bold">إدارة الطلاب</span>
                </a>

                <a href="{{ url('/admin/survey-questions') }}" class="flex flex-col items-center justify-center p-5 bg-slate-50 rounded-2xl hover:bg-purple-50 text-slate-600 hover:text-purple-600 transition-colors gap-3 group border border-transparent hover:border-purple-100">
                    <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-purple-500 group-hover:scale-110 transition-transform">
                        <i class="fas fa-poll text-xl"></i>
                    </div>
                    <span class="text-sm font-bold">الاستبيانات</span>
                </a>
                
                <a href="{{ route('admin.announcements') }}" class="flex flex-col items-center justify-center p-5 bg-slate-50 rounded-2xl hover:bg-red-50 text-slate-600 hover:text-red-500 transition-colors gap-3 group border border-transparent hover:border-red-100">
                    <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
                        <i class="fas fa-bullhorn text-xl"></i>
                    </div>
                    <span class="text-sm font-bold">الإعلانات</span>
                </a>
                
                <a href="#" class="flex flex-col items-center justify-center p-5 bg-slate-50 rounded-2xl hover:bg-slate-100 text-slate-600 hover:text-slate-800 transition-colors gap-3 group">
                    <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-slate-500 group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-export text-xl"></i>
                    </div>
                    <span class="text-sm font-bold">تصدير تقرير</span>
                </a>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="mt-8 bg-[#1e293b] rounded-2xl shadow-lg border border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-history text-amber-500"></i>
                    آخر النشاطات
                </h2>
                <a href="{{ route('admin.all-registrations') }}" class="text-sm text-amber-400 hover:text-amber-300 font-bold">
                    عرض الكل <i class="fas fa-arrow-left mr-1"></i>
                </a>
            </div>

            <div class="space-y-4">
                @forelse($recentActivities as $activity)
                    <div class="flex items-start gap-4 p-4 bg-slate-800/50 rounded-xl border border-slate-700 hover:border-amber-500/30 transition-all">
                        <div class="w-10 h-10 bg-blue-500/10 rounded-full flex items-center justify-center text-blue-400 shrink-0">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-white font-semibold text-sm">
                                سجل الطالب <span class="text-amber-400">{{ $activity->student_name }}</span> 
                                في نشاط <span class="text-blue-400">"{{ $activity->activity_title }}"</span>
                            </p>
                            <p class="text-slate-400 text-xs mt-1">
                                <i class="far fa-clock ml-1"></i>
                                {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400">
                        <i class="fas fa-inbox text-4xl mb-3 text-slate-600"></i>
                        <p>لا توجد نشاطات حديثة</p>
                    </div>
                @endforelse
            </div>
        </div>

    </main>

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