<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الشخصي - {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #f8fafc; }
        .sidebar-link:hover { background-color: #e0e7ff; color: #4338ca; }
        .sidebar-link.active { background-color: #e0e7ff; color: #4338ca; font-weight: bold; }
        .activity-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .activity-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="flex h-screen overflow-hidden bg-gray-50">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-xl hidden md:flex flex-col z-10 border-l border-gray-100">
        <div class="p-6 bg-indigo-600 text-center shadow-lg">
            <div class="w-16 h-16 bg-white rounded-full mx-auto flex items-center justify-center mb-3 shadow-md">
                <i class="fas fa-user-graduate text-3xl text-indigo-600"></i>
            </div>
            <h2 class="text-xl font-bold text-white">لوحة الطالب</h2>
        </div>
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <a href="{{ route('student.dashboard') }}" class="sidebar-link flex items-center p-3 text-gray-600 rounded-xl transition duration-200">
                <i class="fas fa-home ml-3 text-lg"></i> الرئيسية
            </a>
            <a href="{{ route('student.my-activities') }}" class="sidebar-link flex items-center p-3 text-gray-600 rounded-xl transition duration-200">
                <i class="fas fa-calendar-check ml-3 text-lg"></i> أنشطتي
            </a>
            <a href="{{ route('student.favorites') }}" class="sidebar-link flex items-center p-3 text-gray-600 rounded-xl transition duration-200">
                <i class="fas fa-heart ml-3 text-lg"></i> المفضلة
            </a>
            <a href="{{ route('student.profile') }}" class="sidebar-link active flex items-center p-3 rounded-xl transition duration-200">
                <i class="fas fa-user ml-3 text-lg"></i> ملفي الشخصي
            </a>
            <a href="{{ route('profile.edit') }}" class="sidebar-link flex items-center p-3 text-gray-600 rounded-xl transition duration-200">
                <i class="fas fa-cog ml-3 text-lg"></i> الإعدادات
            </a>
        </nav>
        <div class="p-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center p-2 text-red-500 hover:bg-red-50 rounded-lg transition font-bold">
                    <i class="fas fa-sign-out-alt ml-2"></i> تسجيل الخروج
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        
        <!-- Header مع زر الرجوع ✅ -->
        <header class="bg-white shadow-sm p-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <!-- زر الرجوع -->
                <a href="{{ url('/student/dashboard') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                    <i class="fas fa-arrow-right"></i>
                    <span class="font-semibold">رجوع</span>
                </a>
                <h1 class="font-bold text-xl text-indigo-700">الملف الشخصي</h1>
            </div>
            
            <span class="text-gray-500 bg-gray-50 px-4 py-2 rounded-full text-sm border border-gray-100">
                <i class="fas fa-calendar-alt ml-1 text-indigo-500"></i> {{ now()->format('Y/m/d') }}
            </span>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-5xl mx-auto">

                <!-- Profile Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 mb-6 text-white shadow-xl">
                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center text-5xl font-extrabold shadow-lg border-4 border-white/30">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <h1 class="text-3xl font-extrabold mb-1">{{ $user->name }}</h1>
                            <p class="text-indigo-200 text-lg mb-2">
                                <i class="fas fa-envelope ml-2"></i>{{ $user->email }}
                            </p>
                            <span class="bg-white/20 px-4 py-1.5 rounded-full text-sm font-bold border border-white/30">
                                <i class="fas fa-user-graduate ml-1"></i>
                                {{ $user->role === 'admin' ? 'مدير' : 'طالب' }}
                            </span>
                        </div>
                        <div class="mr-auto">
                            <a href="{{ route('profile.edit') }}" class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-xl font-bold transition border border-white/30">
                                <i class="fas fa-edit ml-2"></i> تعديل الملف
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 text-center">
                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-calendar-check text-indigo-600 text-xl"></i>
                        </div>
                        <p class="text-3xl font-extrabold text-gray-800">{{ $activities->count() }}</p>
                        <p class="text-gray-500 text-sm mt-1">نشاط مشترك</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 text-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-star text-yellow-500 text-xl"></i>
                        </div>
                        <p class="text-3xl font-extrabold text-gray-800">{{ $user->points ?? 0 }}</p>
                        <p class="text-gray-500 text-sm mt-1">نقطة مكتسبة</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 text-center">
                        <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-heart text-pink-500 text-xl"></i>
                        </div>
                        <p class="text-3xl font-extrabold text-gray-800">{{ $favorites }}</p>
                        <p class="text-gray-500 text-sm mt-1">نشاط مفضل</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-certificate text-green-500 text-xl"></i>
                        </div>
                        <p class="text-3xl font-extrabold text-gray-800">
                            {{ $activities->where('certificate', true)->count() }}
                        </p>
                        <p class="text-gray-500 text-sm mt-1">شهادة</p>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                    <h3 class="font-bold text-gray-800 text-lg mb-4">
                        <i class="fas fa-chart-line text-indigo-500 ml-2"></i>
                        مستوى النشاط
                    </h3>
                    @php
                        $level = 'مبتدئ';
                        $levelColor = 'bg-gray-400';
                        $percentage = 0;
                        $points = $user->points ?? 0;
                        if ($points >= 500) { $level = 'خبير'; $levelColor = 'bg-purple-500'; $percentage = 100; }
                        elseif ($points >= 200) { $level = 'متقدم'; $levelColor = 'bg-blue-500'; $percentage = 75; }
                        elseif ($points >= 100) { $level = 'متوسط'; $levelColor = 'bg-green-500'; $percentage = 50; }
                        elseif ($points >= 50) { $level = 'مبتدئ متقدم'; $levelColor = 'bg-yellow-500'; $percentage = 25; }
                        else { $percentage = max(5, ($points / 50) * 25); }
                    @endphp
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-bold text-gray-700">{{ $level }}</span>
                        <span class="text-gray-500 text-sm">{{ $points }} نقطة</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="{{ $levelColor }} h-4 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-400 mt-1">
                        <span>مبتدئ</span>
                        <span>متوسط</span>
                        <span>متقدم</span>
                        <span>خبير</span>
                    </div>
                </div>

                <!-- Activities -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="font-bold text-gray-800 text-lg">
                            <i class="fas fa-calendar-check text-indigo-500 ml-2"></i>
                            الأنشطة المشتركة
                        </h3>
                        <a href="{{ route('student.my-activities') }}" class="text-indigo-600 text-sm font-semibold hover:underline">
                            عرض الكل
                        </a>
                    </div>

                    @if($activities->count() > 0)
                        <div class="space-y-3">
                            @foreach($activities->take(5) as $activity)
                                <div class="activity-card flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                                    <div class="w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center text-white flex-shrink-0">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-800">{{ $activity->title }}</p>
                                        <div class="flex items-center gap-3 mt-1">
                                            @if($activity->date)
                                                <span class="text-gray-500 text-xs">
                                                    <i class="fas fa-calendar text-blue-400 ml-1"></i>
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
                                        @if($activity->status === 'active')
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">نشط</span>
                                        @elseif($activity->status === 'completed')
                                            <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold">منتهي</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10">
                            <i class="fas fa-calendar-times text-5xl text-gray-200 mb-3"></i>
                            <p class="text-gray-500 font-semibold">لم تشترك في أي نشاط بعد</p>
                            <a href="{{ route('activities.index') }}" class="inline-block mt-4 bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-indigo-700 transition text-sm">
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