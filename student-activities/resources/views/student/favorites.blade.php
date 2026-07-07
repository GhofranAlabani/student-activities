<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الأنشطة المفضلة</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
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
        
        .activity-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .activity-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(10, 25, 41, 0.1); }
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
            <a href="{{ route('student.favorites') }}" class="sidebar-link active flex items-center p-3 rounded-xl transition duration-200">
                <i class="fas fa-heart ml-3 text-lg"></i> المفضلة
            </a>
            <a href="{{ route('staff.attendance.index', $activity->id ?? 1) }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
    <i class="fas fa-clipboard-check text-gold w-5"></i>
    <span class="font-bold">الحضور</span>
</a>
<a href="{{ route('attendance.scan') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
    <i class="fas fa-qrcode text-gold w-5"></i>
    <span class="font-bold">تسجيل الحضور</span>
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
        <header class="bg-white shadow-md p-4 flex justify-between items-center border-b-2 border-gold/20">
            <div class="flex items-center gap-3">
                <a href="{{ route('student.dashboard') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 hover:bg-gray-100 text-navy rounded-lg transition border border-gray-200">
                    <i class="fas fa-arrow-right"></i>
                    <span class="font-semibold">رجوع</span>
                </a>
                <h1 class="font-black text-xl text-navy flex items-center gap-2 border-r-2 border-gold pr-3 mr-1">
                    الأنشطة المفضلة <i class="fas fa-heart text-red-500"></i>
                </h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-gray-700 bg-gold/10 px-4 py-2 rounded-full text-sm border border-gold/30">
                    <i class="fas fa-calendar-alt ml-1 text-gold"></i> {{ now()->format('Y/m/d') }}
                </span>
                <div class="w-9 h-9 bg-gold rounded-full flex items-center justify-center text-navy font-bold shadow-md">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 bg-[#f5f0e8]">
            <div class="max-w-6xl mx-auto">

                @if($favorites->count() > 0)
                    <p class="text-gray-600 mb-6 font-semibold flex items-center gap-2">
                        <i class="fas fa-heart text-red-500"></i>
                        لديك {{ $favorites->count() }} نشاط في قائمة المفضلة
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($favorites as $activity)
                            <div class="activity-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full relative">
                                
                                <!-- زر إزالة من المفضلة (عائم) -->
                                <div class="absolute top-3 left-3 z-10">
                                    <form action="{{ route('activities.favorite', $activity->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-white/90 backdrop-blur-sm p-2 rounded-full shadow-lg hover:bg-red-50 hover:text-red-600 transition group" title="إزالة من المفضلة">
                                            <i class="fas fa-heart text-red-500 group-hover:scale-110 transition-transform"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Image Area -->
                                <div class="h-40 bg-gradient-to-br from-navy to-navy-light relative flex items-center justify-center">
                                    @if($activity->image)
                                        <img src="{{ asset('storage/' . $activity->image) }}" class="w-full h-full object-cover opacity-90">
                                    @else
                                        <i class="fas fa-calendar-alt text-5xl text-white/20"></i>
                                    @endif
                                    
                                    <!-- Status Badge -->
                                    @if($activity->status === 'مفتوح')
                                        <span class="absolute bottom-3 right-3 bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">متاح للتسجيل</span>
                                    @elseif($activity->status === 'منتهي')
                                        <span class="absolute bottom-3 right-3 bg-gray-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">انتهى النشاط</span>
                                    @endif
                                </div>

                                <div class="p-5 flex-1 flex flex-col">
                                    <!-- Tags -->
                                    <div class="flex justify-between items-start mb-3">
                                        <span class="bg-navy/10 text-navy text-xs font-bold px-3 py-1 rounded-full">
                                            {{ $activity->activityType->name ?? 'عام' }}
                                        </span>
                                        @if($activity->points)
                                            <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                                                <i class="fas fa-star text-yellow-500"></i> {{ $activity->points }}
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="font-black text-navy text-lg mb-2 line-clamp-1">{{ $activity->title }}</h3>
                                    <p class="text-gray-500 text-sm mb-4 line-clamp-2 flex-1">{{ Str::limit($activity->description, 80) }}</p>

                                    <!-- Info Details -->
                                    <div class="space-y-2 mb-4 text-sm text-gray-600">
                                        @if($activity->date)
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-calendar text-gold w-5 text-center"></i>
                                                <span>{{ \Carbon\Carbon::parse($activity->date)->format('Y/m/d') }}</span>
                                            </div>
                                        @endif
                                        @if($activity->location)
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-map-marker-alt text-gold w-5 text-center"></i>
                                                <span class="truncate">{{ $activity->location }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex gap-3 mt-auto pt-4 border-t border-gray-100">
                                        <a href="{{ route('activities.show', $activity->id) }}"
                                            class="flex-1 bg-navy text-white text-center py-2.5 rounded-xl hover:bg-gold hover:text-navy transition font-bold text-sm flex items-center justify-center gap-2">
                                            <i class="fas fa-eye"></i> التفاصيل
                                        </a>
                                        
                                        @if(!$activity->users->contains(auth()->id()) && $activity->status === 'مفتوح')
                                            <form action="{{ route('activities.register', $activity->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-4 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition font-bold" title="تسجيل سريع">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @elseif($activity->users->contains(auth()->id()))
                                            <span class="px-4 py-2.5 bg-gray-100 text-gray-500 rounded-xl text-sm font-bold flex items-center gap-2" title="أنت مسجل بالفعل">
                                                <i class="fas fa-check-circle"></i> مسجل
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-2xl shadow-sm border border-dashed border-gray-300 p-16 text-center max-w-2xl mx-auto mt-10">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-heart text-4xl text-gray-300"></i>
                        </div>
                        <h3 class="text-xl font-black text-navy mb-2">قائمة المفضلة فارغة</h3>
                        <p class="text-gray-500 mb-8 max-w-md mx-auto">اضغط على أيقونة القلب ❤️ في أي نشاط لإضافته إلى مفضلتك والرجوع إليه لاحقاً بسهولة.</p>
                        <a href="{{ route('activities.index') }}"
                            class="inline-block bg-gold text-navy px-8 py-3 rounded-xl font-bold hover:bg-yellow-600 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-search ml-2"></i> اكتشف الأنشطة
                        </a>
                    </div>
                @endif

            </div>
        </main>
    </div>

</body>
</html>