<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المفضلة</title>
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
            <a href="{{ route('student.favorites') }}" class="sidebar-link active flex items-center p-3 rounded-xl transition duration-200">
                <i class="fas fa-heart ml-3 text-lg"></i> المفضلة
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

    <!-- Main -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm p-4 flex justify-between items-center">
    <div class="flex items-center gap-3">
        <!-- زر الرجوع -->
        <a href="{{ route('student.dashboard') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
            <i class="fas fa-arrow-right"></i>
            <span class="font-semibold">رجوع</span>
        </a>
        <h1 class="font-bold text-xl text-indigo-700">
            <i class="fas fa-heart text-pink-500 ml-2"></i>
            الأنشطة المفضلة
        </h1>
    </div>
            <div class="flex items-center gap-3">
                <span class="text-gray-500 bg-gray-50 px-4 py-2 rounded-full text-sm border border-gray-100">
                    <i class="fas fa-calendar-alt ml-1 text-indigo-500"></i> {{ now()->format('Y/m/d') }}
                </span>
                <div class="w-9 h-9 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-6xl mx-auto">

                @if($favorites->count() > 0)
                    <p class="text-gray-500 mb-6">
                        <i class="fas fa-heart text-pink-400 ml-1"></i>
                        لديك {{ $favorites->count() }} نشاط في المفضلة
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($favorites as $activity)
                            <div class="activity-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="h-36 bg-gradient-to-br from-pink-500 to-rose-600 relative">
                                    @if($activity->image)
                                        <img src="{{ asset('storage/' . $activity->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-calendar-alt text-5xl text-white/20"></i>
                                        </div>
                                    @endif
                                    <!-- Remove from favorites -->
                                    <div class="absolute top-2 left-2">
                                        <form action="{{ route('activities.favorite', $activity->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-white/90 p-2 rounded-full shadow hover:bg-white transition">
                                                <i class="fas fa-heart text-red-500 text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @if($activity->status === 'active')
                                        <span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">متاح</span>
                                    @endif
                                </div>

                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="bg-pink-50 text-pink-700 text-xs font-bold px-2 py-1 rounded-full border border-pink-100">
                                            {{ $activity->activityType->name ?? 'عام' }}
                                        </span>
                                        @if($activity->points)
                                            <span class="bg-yellow-50 text-yellow-700 text-xs font-bold px-2 py-1 rounded-full border border-yellow-100">
                                                <i class="fas fa-star text-yellow-400"></i> {{ $activity->points }}
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="font-bold text-gray-800 mb-2 line-clamp-1">{{ $activity->title }}</h3>
                                    <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ Str::limit($activity->description, 80) }}</p>

                                    <div class="space-y-1 mb-4 text-xs text-gray-500">
                                        @if($activity->date)
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-calendar text-blue-400 w-3"></i>
                                                <span>{{ \Carbon\Carbon::parse($activity->date)->format('Y/m/d') }}</span>
                                            </div>
                                        @endif
                                        @if($activity->location)
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-map-marker-alt text-red-400 w-3"></i>
                                                <span class="truncate">{{ $activity->location }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex gap-2">
                                        <a href="{{ route('activities.show', $activity->id) }}"
                                            class="flex-1 bg-indigo-600 text-white text-center py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                                            <i class="fas fa-eye ml-1"></i> التفاصيل
                                        </a>
                                        @if(!$activity->users->contains(auth()->id()) && $activity->status === 'active')
                                            <form action="{{ route('activities.register', $activity->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
                        <div class="w-24 h-24 bg-pink-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-heart text-4xl text-pink-200"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-600 mb-2">قائمة المفضلة فارغة</h3>
                        <p class="text-gray-400 mb-6">أضف الأنشطة التي تعجبك إلى المفضلة بالضغط على أيقونة القلب</p>
                        <a href="{{ route('activities.index') }}"
                            class="inline-block bg-pink-500 text-white px-8 py-3 rounded-xl font-bold hover:bg-pink-600 transition shadow-lg">
                            <i class="fas fa-search ml-2"></i> اكتشف الأنشطة
                        </a>
                    </div>
                @endif

            </div>
        </main>
    </div>

</body>
</html>