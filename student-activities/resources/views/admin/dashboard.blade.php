@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<!-- إضافة Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="min-h-screen bg-[#f8f6f0]" style="font-family: 'Cairo', sans-serif;">
    
    <!-- Header (Top Bar) -->
    <header class="bg-[#0f172a] border-b border-slate-800 sticky top-0 z-50">
        <div class="flex items-center justify-between px-6 py-3">
            
            <!-- 1. التاريخ (يمين) -->
            <div class="flex items-center gap-4">
                <div class="bg-slate-800 px-4 py-2 rounded-lg text-sm font-semibold text-slate-300 flex items-center gap-2 border border-slate-700">
                    <i class="far fa-calendar-alt text-amber-500"></i>
                    <span>{{ now()->format('Y/m/d') }}</span>
                </div>
            </div>

            <!-- 2. القوائم المنسدلة (يسار) -->
            <div class="flex items-center gap-4">
                
                <!-- 🔔 قائمة الإشعارات المنسدلة -->
                <div class="relative" x-data="{ open: false }">
                    <!-- زر الفتح - بدون @click.away هنا -->
                    <button @click="open = !open" 
                            class="relative p-2 text-slate-400 hover:text-amber-400 transition rounded-full hover:bg-slate-800">
                        <i class="fas fa-bell text-xl"></i>
                        @if($notificationsCount > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center border-2 border-[#0f172a] animate-pulse">
                                {{ $notificationsCount }}
                            </span>
                        @endif
                    </button>

                    <!-- محتوى القائمة المنسدلة - مع @click.away هنا -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute left-0 mt-2 w-80 bg-[#1e293b] border border-slate-700 rounded-xl shadow-2xl overflow-hidden z-50">
                        
                        <div class="px-4 py-3 border-b border-slate-700 bg-slate-800/50 flex justify-between items-center">
                            <h3 class="font-bold text-white flex items-center gap-2">
                                <i class="fas fa-bell text-amber-500"></i> الإشعارات
                            </h3>
                        </div>

                        <div class="max-h-80 overflow-y-auto">
                            @forelse($fullActivities as $act)
                                <div class="px-4 py-3 border-b border-slate-700/50 hover:bg-slate-800/50 transition">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 bg-green-500/10 rounded-full flex items-center justify-center text-green-400 shrink-0 mt-1">
                                            <i class="fas fa-check text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-white text-sm font-semibold leading-tight">اكتمل العدد: {{ Str::limit($act->title, 25) }}</p>
                                            <p class="text-slate-400 text-xs mt-1">وصل للحد الأقصى {{ $act->max_participants }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                 <div class="px-4 py-6 text-center text-slate-500 text-sm">لا توجد إشعارات مكتملة</div>
                            @endforelse

                            @foreach($newSurveys as $survey)
                                <div class="px-4 py-3 border-b border-slate-700/50 hover:bg-slate-800/50 transition">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 bg-blue-500/10 rounded-full flex items-center justify-center text-blue-400 shrink-0 mt-1">
                                            <i class="fas fa-poll text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-white text-sm font-semibold leading-tight">استبيان جديد: {{ Str::limit($survey->question, 25) }}</p>
                                            <p class="text-slate-400 text-xs mt-1">{{ \Carbon\Carbon::parse($survey->created_at)->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 👤 قائمة الملف الشخصي المنسدلة -->
                <div class="relative" x-data="{ open: false }">
                    <!-- زر الفتح - بدون @click.away هنا -->
                    <button @click="open = !open" 
                            class="flex items-center gap-3 pl-2 py-1 rounded-lg hover:bg-slate-800 transition border-l border-slate-700">
                        <div class="text-right hidden md:block">
                            <p class="text-sm font-bold text-white">المشرف العام</p>
                            <p class="text-xs text-slate-400">مدير النظام</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=Admin&background=d4a017&color=fff" 
                             class="w-10 h-10 rounded-full border-2 border-slate-700 shadow-sm">
                    </button>

                    <!-- محتوى القائمة المنسدلة - مع @click.away هنا -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute left-0 mt-2 w-56 bg-[#1e293b] border border-slate-700 rounded-xl shadow-2xl overflow-hidden z-50">
                        
                        <div class="px-4 py-3 border-b border-slate-700 bg-slate-800/50">
                            <p class="text-white font-bold">المشرف العام</p>
                            <p class="text-slate-400 text-xs">admin@university.edu</p>
                        </div>

                        <div class="py-2">
                            <a href="#" class="flex items-center gap-3 px-4 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition text-sm">
                                <i class="fas fa-user w-5 text-center text-amber-500"></i> الملف الشخصي
                            </a>
                            <a href="#" class="flex items-center gap-3 px-4 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition text-sm">
                                <i class="fas fa-cog w-5 text-center text-blue-500"></i> الإعدادات
                            </a>
                            <a href="#" class="flex items-center gap-3 px-4 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition text-sm">
                                <i class="fas fa-lock w-5 text-center text-green-500"></i> كلمة المرور
                            </a>
                        </div>

                        <div class="border-t border-slate-700 py-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-red-400 hover:bg-red-500/10 hover:text-red-300 transition text-sm">
                                    <i class="fas fa-sign-out-alt w-5 text-center"></i> تسجيل الخروج
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

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
                <!-- Action 1: إضافة نشاط -->
                <a href="{{ route('activities.create') }}" class="flex flex-col items-center justify-center p-5 bg-slate-50 rounded-2xl hover:bg-blue-50 text-slate-600 hover:text-blue-600 transition-colors gap-3 group border border-transparent hover:border-blue-100">
                    <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                        <i class="fas fa-plus-circle text-xl"></i>
                    </div>
                    <span class="text-sm font-bold">إضافة نشاط جديد</span>
                </a>

                <!-- Action 2: إدارة الطلاب -->
                <a href="{{ route('admin.students') }}" class="flex flex-col items-center justify-center p-5 bg-slate-50 rounded-2xl hover:bg-green-50 text-slate-600 hover:text-green-600 transition-colors gap-3 group border border-transparent hover:border-green-100">
                    <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-green-500 group-hover:scale-110 transition-transform">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <span class="text-sm font-bold">إدارة الطلاب</span>
                </a>

                <!-- Action 3: الاستبيانات -->
                <a href="{{ url('/admin/survey-questions') }}" class="flex flex-col items-center justify-center p-5 bg-slate-50 rounded-2xl hover:bg-purple-50 text-slate-600 hover:text-purple-600 transition-colors gap-3 group border border-transparent hover:border-purple-100">
                    <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-purple-500 group-hover:scale-110 transition-transform">
                        <i class="fas fa-poll text-xl"></i>
                    </div>
                    <span class="text-sm font-bold">الاستبيانات</span>
                </a>
                
                <!-- Action 4: الإعلانات -->
                <a href="{{ route('admin.announcements') }}" class="flex flex-col items-center justify-center p-5 bg-slate-50 rounded-2xl hover:bg-red-50 text-slate-600 hover:text-red-500 transition-colors gap-3 group border border-transparent hover:border-red-100">
                    <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
                        <i class="fas fa-bullhorn text-xl"></i>
                    </div>
                    <span class="text-sm font-bold">الإعلانات</span>
                </a>
                
                <!-- Action 5: تصدير تقرير -->
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

    <!-- Scripts -->
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