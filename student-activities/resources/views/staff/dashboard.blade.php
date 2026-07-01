<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة المشرف - {{ auth()->user()->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .bg-navy { background-color: #0a1929; }
        .bg-navy-light { background-color: #112240; }
        .text-gold { color: #d4a017; }
        .bg-gold { background-color: #d4a017; }
        .bg-gold:hover { background-color: #b8860b; }
        .border-gold { border-color: #d4a017; }
        
        /* Sidebar Styles */
        .sidebar-link { transition: all 0.3s ease; }
        .sidebar-link:hover { 
            background-color: rgba(212, 160, 23, 0.1); 
            transform: translateX(-5px); 
        }
        .sidebar-link.active { 
            background-color: rgba(212, 160, 23, 0.2); 
            border-right: 3px solid #d4a017; 
        }
    </style>
</head>
<body class="bg-[#f5f0e8] min-h-screen">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-72 bg-navy text-white fixed right-0 top-0 h-full shadow-2xl z-50 overflow-y-auto">
            <!-- Logo -->
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

            <!-- Navigation -->
            <nav class="p-4 space-y-2">
                <a href="{{ route('staff.dashboard') }}" class="sidebar-link active flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
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

                <a href="{{ route('staff.reports.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-chart-bar text-gold w-5"></i>
                    <span class="font-bold">التقارير</span>
                </a>

                @if($myActivities->count() > 0)
                    @php $firstActivity = $myActivities->first(); @endphp
                    <a href="{{ route('staff.attendance.index', $firstActivity->id) }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                        <i class="fas fa-clipboard-check text-gold w-5"></i>
                        <span class="font-bold">الحضور</span>
                    </a>
                @endif

                <a href="{{ route('staff.announcements.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-bullhorn text-gold w-5"></i>
                    <span class="font-bold">الإعلانات</span>
                </a>

                <div class="border-t border-white/10 my-4"></div>

                <a href="{{ route('staff.settings.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-cog text-gold w-5"></i>
                    <span class="font-bold">الإعدادات</span>
                </a>
            </nav>

            <!-- Logout -->
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
                        <h1 class="text-2xl font-black text-navy">لوحة تحكم المشرف</h1>
                        <p class="text-sm text-gray-500 mt-1">إدارة الأنشطة ومتابعة التسجيلات</p>
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

            <!-- Main Content Area -->
            <main class="p-8">
                
                <!-- رسائل النجاح -->
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- إحصائيات سريعة -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-2xl shadow-lg border-r-4 border-gold">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">أنشطتي</p>
                                <h3 class="text-3xl font-black text-navy">{{ $myActivitiesCount }}</h3>
                            </div>
                            <div class="w-12 h-12 bg-gold/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar text-gold text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-lg border-r-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">تسجيلات معلقة</p>
                                <h3 class="text-3xl font-black text-navy">{{ $pendingRegistrations->count() }}</h3>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-blue-500 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-lg border-r-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">إجمالي المشاركين</p>
                                <h3 class="text-3xl font-black text-navy">{{ $totalParticipants }}</h3>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-users text-green-500 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-lg border-r-4 border-purple-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">متوسط التقييم</p>
                                <h3 class="text-3xl font-black text-navy">{{ number_format($avgRating, 1) }} ⭐</h3>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-star text-purple-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- أنشطتي -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-black text-navy flex items-center gap-2">
                            <i class="fas fa-calendar-check text-gold"></i>
                            أنشطتي
                        </h2>
                        <a href="{{ route('staff.activities.create') }}" class="bg-gold text-navy px-4 py-2 rounded-lg font-bold hover:bg-yellow-600 transition">
                            <i class="fas fa-plus ml-1"></i> إضافة نشاط
                        </a>
                    </div>

                    @if($myActivities->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-right text-sm font-bold text-navy">النشاط</th>
                                        <th class="px-4 py-3 text-right text-sm font-bold text-navy">النوع</th>
                                        <th class="px-4 py-3 text-right text-sm font-bold text-navy">التاريخ</th>
                                        <th class="px-4 py-3 text-right text-sm font-bold text-navy">المسجلين</th>
                                        <th class="px-4 py-3 text-right text-sm font-bold text-navy">الحالة</th>
                                        <th class="px-4 py-3 text-right text-sm font-bold text-navy">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($myActivities as $activity)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-4">
                                            <div class="font-bold text-navy">{{ $activity->title }}</div>
                                            <div class="text-xs text-gray-500">{{ Str::limit($activity->description, 50) }}</div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="bg-navy/10 text-navy text-xs px-3 py-1 rounded-full">
                                                {{ $activity->activityType->name ?? 'عام' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($activity->date)->format('Y/m/d') }}
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="font-bold text-navy">{{ $activity->registrations_count }}</span>
                                            <span class="text-gray-500 text-sm">/ {{ $activity->max_participants ?? '∞' }}</span>
                                        </td>
                                        <td class="px-4 py-4">
                                            @if($activity->status === 'مفتوح')
                                                <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-bold">
                                                    <i class="fas fa-check-circle ml-1"></i>مفتوح
                                                </span>
                                            @else
                                                <span class="bg-gray-100 text-gray-600 text-xs px-3 py-1 rounded-full font-bold">
                                                    <i class="fas fa-pause-circle ml-1"></i>مغلق
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex gap-2">
                                                <a href="{{ route('staff.activities.show', $activity->id) }}" 
                                                   class="text-blue-600 hover:text-blue-800" title="التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('staff.activities.edit', $activity->id) }}" 
                                                   class="text-gold hover:text-yellow-700" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('staff.registrations.index', $activity->id) }}" 
                                                   class="text-green-600 hover:text-green-800" title="التسجيلات">
                                                    <i class="fas fa-users"></i>
                                                </a>
                                                <a href="{{ route('staff.attendance.index', $activity->id) }}" 
                                                   class="text-indigo-600 hover:text-indigo-800" title="الحضور">
                                                    <i class="fas fa-clipboard-check"></i>
                                                </a>
                                                <a href="{{ route('staff.report.show', $activity->id) }}" 
                                                   class="text-purple-600 hover:text-purple-800" title="التقرير">
                                                    <i class="fas fa-chart-bar"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg mb-4">لا توجد أنشطة حتى الآن</p>
                            <a href="{{ route('staff.activities.create') }}" class="inline-block bg-gold text-navy px-6 py-3 rounded-xl font-bold hover:bg-yellow-600 transition">
                                <i class="fas fa-plus ml-2"></i> أضف أول نشاط
                            </a>
                        </div>
                    @endif
                </div>

                <!-- التسجيلات المعلقة -->
                @if($pendingRegistrations->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-2xl font-black text-navy flex items-center gap-2 mb-6">
                        <i class="fas fa-clock text-blue-500"></i>
                        تسجيلات بانتظار الموافقة
                    </h2>
                    <div class="space-y-3">
                        @foreach($pendingRegistrations as $registration)
                            @if($registration->user && $registration->activity)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-navy rounded-full flex items-center justify-center text-white font-bold">
                                        {{ substr($registration->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-navy">{{ $registration->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $registration->activity->title }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <form action="{{ route('staff.registrations.approve', $registration->id) }}" method="POST">
                                        @csrf
                                        <button class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition text-sm">
                                            <i class="fas fa-check ml-1"></i> قبول
                                        </button>
                                    </form>
                                    <form action="{{ route('staff.registrations.reject', $registration->id) }}" method="POST">
                                        @csrf
                                        <button class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition text-sm">
                                            <i class="fas fa-times ml-1"></i> رفض
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

            </main>
        </div>

    </div>

</body>
</html>