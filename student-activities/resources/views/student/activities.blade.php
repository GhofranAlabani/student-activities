<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تصفح الأنشطة</title>
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
            <a href="{{ route('student.activities') }}" class="sidebar-link active flex items-center p-3 rounded-xl transition duration-200">
                <i class="fas fa-calendar-alt ml-3 text-lg"></i> تصفح الأنشطة
            </a>
            <a href="{{ route('student.my-activities') }}" class="sidebar-link flex items-center p-3 text-gray-300 rounded-xl transition duration-200">
                <i class="fas fa-calendar-check ml-3 text-lg"></i> سجل الأنشطة
            </a>
            <a href="{{ route('student.favorites') }}" class="sidebar-link flex items-center p-3 text-gray-300 rounded-xl transition duration-200">
                <i class="fas fa-heart ml-3 text-lg"></i> المفضلة
            </a>
            <a href="{{ route('attendance.index') }}" class="sidebar-link flex items-center p-3 text-gray-300 rounded-xl transition duration-200">
                <i class="fas fa-clipboard-check ml-3 text-lg"></i> الحضور
            </a>
            <a href="{{ route('attendance.scan') }}" class="sidebar-link flex items-center p-3 text-gray-300 rounded-xl transition duration-200">
                <i class="fas fa-qrcode ml-3 text-lg"></i> تسجيل الحضور
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
                <h1 class="font-black text-xl text-navy border-r-2 border-gold pr-3 mr-1">تصفح الأنشطة المتاحة</h1>
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

                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
                        <i class="fas fa-check-circle text-green-500 text-lg"></i> {{ session('success') }}
                    </div>
                @endif

                <!-- Activities Grid -->
                @if(count($activities) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($activities as $activity)
                            <div class="activity-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                                <!-- Image Area -->
                                <div class="h-40 bg-gradient-to-br from-navy to-navy-light relative flex items-center justify-center overflow-hidden">
                                    @if($activity->image)
                                        <img src="{{ asset('storage/' . $activity->image) }}" class="w-full h-full object-cover opacity-90">
                                    @else
                                        @php
                                            $defaultImages = [
                                                'مؤتمر' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=400&h=300&fit=crop',
                                                'ورشة عمل' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=400&h=300&fit=crop',
                                                'ندوة' => 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=400&h=300&fit=crop',
                                                'دورة' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=400&h=300&fit=crop',
                                            ];
                                            $typeName = $activity->activityType->name ?? 'عام';
                                            $defaultImage = $defaultImages[$typeName] ?? 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=400&h=300&fit=crop';
                                        @endphp
                                        <img src="{{ $defaultImage }}" class="w-full h-full object-cover opacity-50">
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                                            <i class="fas fa-calendar-alt text-5xl text-white/70"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- Status Badge -->
                                    <span class="absolute top-3 right-3 bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                        متاح للتسجيل
                                    </span>
                                </div>

                                <div class="p-5 flex-1 flex flex-col">
                                    <!-- Tags -->
                                    <div class="flex justify-between items-start mb-3">
                                        <span class="bg-navy/10 text-navy text-xs font-bold px-3 py-1 rounded-full">
                                            {{ $activity->activityType->name ?? 'عام' }}
                                        </span>
                                        @if($activity->points)
                                            <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                                                <i class="fas fa-star text-yellow-500"></i> {{ $activity->points }} نقطة
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="font-black text-navy text-lg mb-3 line-clamp-1">{{ $activity->title }}</h3>
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $activity->description }}</p>

                                    <!-- Info Details -->
                                    <div class="space-y-2 mb-4 text-sm text-gray-600 flex-1">
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
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-users text-gold w-5 text-center"></i>
                                            <span>السعة: {{ $activity->registrations_count ?? $activity->capacity ?? 'غير محدد' }}</span>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex gap-2 mt-auto pt-4 border-t border-gray-100">
                                        <a href="{{ route('activities.show', $activity->id) }}"
                                            class="flex-1 bg-navy text-white text-center py-2 rounded-xl hover:bg-gold hover:text-navy transition font-bold text-sm flex items-center justify-center gap-1">
                                            <i class="fas fa-eye"></i> التفاصيل
                                        </a>
                                        
                                        <form action="{{ route('activities.register', $activity->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full bg-gold text-navy text-center py-2 rounded-xl hover:bg-yellow-600 transition font-bold text-sm flex items-center justify-center gap-1"
                                                    onclick="return confirm('هل تريد التسجيل في هذا النشاط؟')">
                                                <i class="fas fa-plus"></i> تسجيل
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-2xl shadow-sm border border-dashed border-gray-300 p-16 text-center">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-calendar-times text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-black text-navy mb-2">لا توجد أنشطة متاحة حالياً</h3>
                        <p class="text-gray-500 max-w-md mx-auto">ترقب الأنشطة القادمة! سيتم إضافة أنشطة جديدة قريباً.</p>
                    </div>
                @endif

            </div>
        </main>
    </div>

</body>
</html>