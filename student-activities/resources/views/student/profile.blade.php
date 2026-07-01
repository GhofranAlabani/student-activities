<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الشخصي - {{ $user->name }}</title>
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
        
        .sidebar-link:hover { background-color: rgba(212, 160, 23, 0.1); color: #d4a017; }
        .sidebar-link.active { background-color: rgba(212, 160, 23, 0.2); color: #d4a017; font-weight: bold; border-right: 3px solid #d4a017; }
        
        .activity-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .activity-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(10, 25, 41, 0.1); }
        
        /* تأثير الشارات */
        .badge-card { transition: all 0.3s ease; position: relative; overflow: hidden; }
        .badge-card:hover { transform: translateY(-5px); }
        .badge-earned {
            background: linear-gradient(135deg, #fef9e7 0%, #fdf4d1 100%);
            border: 2px solid #d4a017;
            box-shadow: 0 4px 15px rgba(212, 160, 23, 0.2);
        }
        .badge-earned::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(212, 160, 23, 0.1), transparent);
            animation: shimmer 3s infinite;
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        .badge-locked {
            background: #f3f4f6;
            filter: grayscale(100%);
            opacity: 0.6;
        }
        .badge-icon {
            font-size: 3rem;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }
        .badge-earned .badge-icon {
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden bg-[#f5f0e8]">

    <!-- Sidebar -->
    <aside class="w-64 bg-navy shadow-2xl hidden md:flex flex-col z-10 border-l border-gold/20">
        <div class="p-6 bg-navy-light text-center shadow-lg border-b border-gold/20">
            <div class="w-16 h-16 bg-gold rounded-full mx-auto flex items-center justify-center mb-3 shadow-xl">
                <i class="fas fa-user-graduate text-3xl text-navy"></i>
            </div>
            <h2 class="text-xl font-bold text-gold">لوحة الطالب</h2>
        </div>
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <a href="{{ route('student.dashboard') }}" class="sidebar-link flex items-center p-3 text-gray-300 rounded-xl transition duration-200">
                <i class="fas fa-home ml-3 text-lg"></i> الرئيسية
            </a>
            <a href="{{ route('student.my-activities') }}" class="sidebar-link flex items-center p-3 text-gray-300 rounded-xl transition duration-200">
                <i class="fas fa-calendar-check ml-3 text-lg"></i> أنشطتي
            </a>
            <a href="{{ route('student.favorites') }}" class="sidebar-link flex items-center p-3 text-gray-300 rounded-xl transition duration-200">
                <i class="fas fa-heart ml-3 text-lg"></i> المفضلة
            </a>
            <a href="{{ route('attendance.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                <i class="fas fa-clipboard-list text-gold w-5"></i>
                <span class="font-bold">سجل الحضور</span>
            </a>
            <a href="{{ route('attendance.scan') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                <i class="fas fa-qrcode text-gold w-5"></i>
                <span class="font-bold">تسجيل الحضور</span>
            </a>
            <a href="{{ route('student.profile') }}" class="sidebar-link active flex items-center p-3 rounded-xl transition duration-200">
                <i class="fas fa-user ml-3 text-lg"></i> ملفي الشخصي
            </a>
            <a href="{{ route('profile.edit') }}" class="sidebar-link flex items-center p-3 text-gray-300 rounded-xl transition duration-200">
                <i class="fas fa-cog ml-3 text-lg"></i> الإعدادات
            </a>
        </nav>
        <div class="p-4 border-t border-gold/20">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center p-2 text-red-400 hover:bg-red-500/10 rounded-lg transition font-bold">
                    <i class="fas fa-sign-out-alt ml-2"></i> تسجيل الخروج
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        
        <!-- Header -->
        <header class="bg-white shadow-md p-4 flex justify-between items-center border-b-2 border-gold/20">
            <div class="flex items-center gap-3">
                <a href="{{ url('/student/dashboard') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 hover:bg-gray-100 text-navy rounded-lg transition border border-gray-200">
                    <i class="fas fa-arrow-right"></i>
                    <span class="font-semibold">رجوع</span>
                </a>
                <h1 class="font-black text-xl text-navy border-r-2 border-gold pr-3 mr-1">الملف الشخصي</h1>
            </div>
            
            <span class="text-gray-700 bg-gold/10 px-4 py-2 rounded-full text-sm border border-gold/30">
                <i class="fas fa-calendar-alt ml-1 text-gold"></i> {{ now()->format('Y/m/d') }}
            </span>
        </header>

        <main class="flex-1 overflow-y-auto p-6 bg-[#f5f0e8]">
            <div class="max-w-6xl mx-auto">

                <!-- Profile Header -->
                <div class="bg-gradient-to-br from-navy to-navy-light rounded-2xl p-8 mb-6 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-64 h-64 bg-gold/5 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
                    
                    <div class="relative z-10 flex items-center gap-6 flex-wrap">
                        <div class="w-24 h-24 bg-gold rounded-full flex items-center justify-center text-navy text-5xl font-black shadow-lg border-4 border-white/20">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h1 class="text-3xl font-black mb-1">{{ $user->name }}</h1>
                            <p class="text-gray-300 text-lg mb-3">
                                <i class="fas fa-envelope ml-2 text-gold"></i>{{ $user->email }}
                            </p>
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="bg-white/10 px-4 py-1.5 rounded-full text-sm font-bold border border-white/20 text-gold">
                                    <i class="fas fa-user-graduate ml-1"></i>
                                    {{ $user->role === 'admin' ? 'مدير' : 'طالب' }}
                                </span>
                                <span class="px-4 py-1.5 rounded-full text-sm font-bold border-2" 
                                      style="background: {{ $user->level_name['color'] }}20; border-color: {{ $user->level_name['color'] }}; color: {{ $user->level_name['color'] }}">
                                    {{ $user->level_name['icon'] }} {{ $user->level_name['name'] }}
                                </span>
                            </div>
                        </div>
                        <div class="mr-auto">
                            <a href="{{ route('profile.edit') }}" class="bg-gold text-navy hover:bg-yellow-500 px-6 py-3 rounded-xl font-bold transition shadow-lg flex items-center gap-2">
                                <i class="fas fa-edit"></i> تعديل الملف
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white p-5 rounded-2xl shadow-lg border-b-4 border-indigo-500 text-center hover:scale-105 transition">
                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-calendar-check text-indigo-600 text-xl"></i>
                        </div>
                        <p class="text-3xl font-black text-navy">{{ $activities->count() }}</p>
                        <p class="text-gray-500 text-sm mt-1 font-semibold">نشاط مشترك</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl shadow-lg border-b-4 border-gold text-center hover:scale-105 transition">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-star text-gold text-xl"></i>
                        </div>
                        <p class="text-3xl font-black text-navy">{{ $user->total_points ?? 0 }}</p>
                        <p class="text-gray-500 text-sm mt-1 font-semibold">نقطة مكتسبة</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl shadow-lg border-b-4 border-pink-500 text-center hover:scale-105 transition">
                        <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-heart text-pink-500 text-xl"></i>
                        </div>
                        <p class="text-3xl font-black text-navy">{{ $favorites }}</p>
                        <p class="text-gray-500 text-sm mt-1 font-semibold">نشاط مفضل</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl shadow-lg border-b-4 border-purple-500 text-center hover:scale-105 transition">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-trophy text-purple-500 text-xl"></i>
                        </div>
                        <p class="text-3xl font-black text-navy">{{ count($userBadges) }}</p>
                        <p class="text-gray-500 text-sm mt-1 font-semibold">شارة مكتسبة</p>
                    </div>
                </div>

                <!-- Level Progress -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-black text-navy text-lg flex items-center gap-2">
                            <span style="color: {{ $user->level_name['color'] }}">{{ $user->level_name['icon'] }}</span>
                            المستوى: {{ $user->level_name['name'] }}
                        </h3>
                        <span class="bg-navy/10 text-navy px-3 py-1 rounded-full text-sm font-bold">
                            {{ $user->total_points ?? 0 }} / {{ $user->next_level_points }} نقطة
                        </span>
                    </div>
                    
                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden mb-2">
                        <div class="h-4 rounded-full transition-all duration-1000" 
                             style="width: {{ $user->level_progress }}%; background: linear-gradient(90deg, {{ $user->level_name['color'] }}, #d4a017);">
                        </div>
                    </div>
                    
                    <p class="text-sm text-gray-500 text-center">
                        @if($user->total_points >= 500)
                            🎉 لقد وصلت لأعلى مستوى! استمر في التألق
                        @else
                            تبقي لك <strong class="text-gold">{{ $user->next_level_points - ($user->total_points ?? 0) }}</strong> نقطة للوصول للمستوى التالي
                        @endif
                    </p>
                </div>

                <!-- 🏆 Badges Section -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-black text-navy text-xl flex items-center gap-2">
                            <i class="fas fa-trophy text-gold"></i>
                            الشارات والإنجازات
                        </h3>
                        <span class="bg-gold/20 text-gold px-4 py-2 rounded-full text-sm font-bold">
                            {{ count($userBadges) }} / {{ $badges->count() }} شارة
                        </span>
                    </div>

                    <!-- Earned Badges -->
                    @php
                        $earnedBadges = $badges->filter(fn($b) => in_array($b->id, $userBadges));
                        $lockedBadges = $badges->filter(fn($b) => !in_array($b->id, $userBadges));
                    @endphp

                    @if($earnedBadges->count() > 0)
                        <div class="mb-6">
                            <h4 class="font-bold text-navy mb-3 flex items-center gap-2">
                                <i class="fas fa-crown text-gold"></i>
                                الشارات المكتسبة
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($earnedBadges as $badge)
                                    <div class="badge-card badge-earned rounded-2xl p-4 text-center">
                                        <div class="badge-icon mb-2">{{ $badge->icon }}</div>
                                        <h5 class="font-black text-navy text-sm mb-1">{{ $badge->name }}</h5>
                                        <p class="text-xs text-gray-600 leading-tight">{{ $badge->description }}</p>
                                        <div class="mt-2 inline-block bg-gold/20 text-gold px-2 py-0.5 rounded-full text-xs font-bold">
                                            <i class="fas fa-check"></i> مكتمل
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Locked Badges -->
                    @if($lockedBadges->count() > 0)
                        <div>
                            <h4 class="font-bold text-gray-500 mb-3 flex items-center gap-2">
                                <i class="fas fa-lock"></i>
                                شارات يمكنك كسبها
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($lockedBadges as $badge)
                                    @php
                                        $current = 0;
                                        if ($badge->type === 'points') {
                                            $current = $user->total_points ?? 0;
                                        } elseif ($badge->type === 'activities') {
                                            $current = $activities->count();
                                        } elseif ($badge->type === 'attendance') {
                                            $current = $user->attendanceRecords()->where('status', 'present')->count();
                                        }
                                        $progress = $badge->requirement > 0 ? min(100, ($current / $badge->requirement) * 100) : 0;
                                    @endphp
                                    <div class="badge-card badge-locked rounded-2xl p-4 text-center border-2 border-gray-200">
                                        <div class="badge-icon mb-2 opacity-50">{{ $badge->icon }}</div>
                                        <h5 class="font-black text-navy text-sm mb-1">{{ $badge->name }}</h5>
                                        <p class="text-xs text-gray-500 leading-tight mb-2">{{ $badge->description }}</p>
                                        
                                        <!-- Progress Bar -->
                                        <div class="w-full bg-gray-300 rounded-full h-2 mb-1">
                                            <div class="bg-gold h-2 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 font-bold">{{ $current }} / {{ $badge->requirement }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Recent Activities -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="font-black text-navy text-lg flex items-center gap-2">
                            <i class="fas fa-calendar-check text-gold"></i>
                            الأنشطة المشتركة
                        </h3>
                        <a href="{{ route('student.my-activities') }}" class="text-gold text-sm font-bold hover:text-yellow-700 transition flex items-center gap-1">
                            عرض الكل <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>

                    @if($activities->count() > 0)
                        <div class="space-y-3">
                            @foreach($activities->take(5) as $activity)
                                <div class="activity-card flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-gold/30 transition">
                                    <div class="w-12 h-12 bg-navy rounded-xl flex items-center justify-center text-gold flex-shrink-0 shadow-md">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-navy">{{ $activity->title }}</p>
                                        <div class="flex items-center gap-3 mt-1">
                                            @if($activity->date)
                                                <span class="text-gray-500 text-xs font-medium">
                                                    <i class="fas fa-calendar text-gold ml-1"></i>
                                                    {{ \Carbon\Carbon::parse($activity->date)->format('Y/m/d') }}
                                                </span>
                                            @endif
                                            @if($activity->points)
                                                <span class="text-yellow-600 text-xs font-bold">
                                                    <i class="fas fa-star ml-1"></i>
                                                    {{ $activity->points }} نقطة
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        @if($activity->status === 'مفتوح' || $activity->status === 'active')
                                            <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold border border-emerald-200">نشط</span>
                                        @elseif($activity->status === 'منتهي' || $activity->status === 'completed')
                                            <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold border border-gray-200">منتهي</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                            <i class="fas fa-calendar-times text-5xl text-gray-300 mb-3"></i>
                            <p class="text-navy font-bold">لم تشترك في أي نشاط بعد</p>
                            <a href="{{ route('activities.index') }}" class="inline-block mt-4 bg-gold text-navy px-6 py-2.5 rounded-xl font-bold hover:bg-yellow-500 transition text-sm shadow-md">
                                تصفح الأنشطة
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

</body>
</html>