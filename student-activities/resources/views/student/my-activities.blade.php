<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أنشطتي المسجلة</title>
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
            <a href="{{ route('student.my-activities') }}" class="sidebar-link active flex items-center p-3 rounded-xl transition duration-200">
                <i class="fas fa-calendar-check ml-3 text-lg"></i> سجل الأنشطة
            </a>
            <a href="{{ route('student.favorites') }}" class="sidebar-link flex items-center p-3 text-gray-300 rounded-xl transition duration-200">
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
        <header class="bg-white shadow-md p-4 flex justify-between items-center border-b-2 border-gold/20">
            <div class="flex items-center gap-3">
                <a href="{{ route('student.dashboard') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 hover:bg-gray-100 text-navy rounded-lg transition border border-gray-200">
                    <i class="fas fa-arrow-right"></i>
                    <span class="font-semibold">رجوع</span>
                </a>
                <h1 class="font-black text-xl text-navy border-r-2 border-gold pr-3 mr-1">سجل الأنشطة</h1>
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

                {{-- ✅ حساب الإحصائيات في الأعلى --}}
                @php
                    $totalPoints = $activities->sum(function($activity) {
                        return $activity->pivot ? ($activity->pivot->points_earned ?? $activity->points ?? 0) : 0;
                    });
                    
                    $activeCount = $activities->filter(function($activity) {
                        return in_array($activity->status, ['مفتوح', 'نشط', 'active', 'مكتمل']);
                    })->count();
                @endphp

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                    <div class="bg-white p-5 rounded-2xl shadow-lg border-b-4 border-indigo-500 flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold mb-1">إجمالي الأنشطة</p>
                            <p class="text-4xl font-black text-navy">{{ count($activities) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-layer-group text-xl text-indigo-600"></i>
                        </div>
                    </div>
                    
                    <div class="bg-white p-5 rounded-2xl shadow-lg border-b-4 border-emerald-500 flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold mb-1">أنشطة نشطة</p>
                            <p class="text-4xl font-black text-navy">{{ $activeCount }}</p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-running text-xl text-emerald-600"></i>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl shadow-lg border-b-4 border-gold flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold mb-1">النقاط المكتسبة</p>
                            <p class="text-4xl font-black text-navy">{{ $totalPoints }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-star text-xl text-gold"></i>
                        </div>
                    </div>
                </div>

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
                                        {{-- ✅ صورة افتراضية حسب نوع النشاط --}}
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
                                    @if($activity->status === 'مفتوح')
                                        <span class="absolute top-3 right-3 bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">نشط</span>
                                    @elseif($activity->status === 'منتهي')
                                        <span class="absolute top-3 right-3 bg-gray-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">منتهي</span>
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

                                    <h3 class="font-black text-navy text-lg mb-3 line-clamp-1">{{ $activity->title }}</h3>

                                    <!-- Info Details -->
                                    <div class="space-y-2 mb-4 text-sm text-gray-600 flex-1">
                                        @if($activity->date)
                                            @php
                                                $activityDate = \Carbon\Carbon::parse($activity->date);
                                                $formattedDate = $activityDate->year > 2020 
                                                    ? $activityDate->format('Y/m/d') 
                                                    : now()->format('Y/m/d');
                                            @endphp
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-calendar text-gold w-5 text-center"></i>
                                                <span>{{ $formattedDate }}</span>
                                            </div>
                                        @endif
                                        @if($activity->location)
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-map-marker-alt text-gold w-5 text-center"></i>
                                                <span class="truncate">{{ $activity->location }}</span>
                                            </div>
                                        @endif
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-clock text-gold w-5 text-center"></i>
                                            <span>سجلت في: {{ $activity->pivot && $activity->pivot->created_at ? \Carbon\Carbon::parse($activity->pivot->created_at)->format('Y/m/d H:i') : 'غير متوفر' }}</span>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex gap-2 mt-auto pt-4 border-t border-gray-100">
                                        <a href="{{ route('activities.show', $activity->id) }}"
                                            class="flex-1 bg-navy text-white text-center py-2 rounded-xl hover:bg-gold hover:text-navy transition font-bold text-sm flex items-center justify-center gap-1">
                                            <i class="fas fa-eye"></i> التفاصيل
                                        </a>
                                        
                                        @if($activity->date && \Carbon\Carbon::parse($activity->date)->isFuture())
                                            <a href="{{ route('activities.export-calendar', $activity->id) }}" 
                                               target="_blank"
                                               class="flex-1 bg-blue-600 text-white text-center py-2 rounded-xl hover:bg-blue-700 transition font-bold text-sm flex items-center justify-center gap-1"
                                               title="إضافة إلى تقويم جوجل">
                                                <i class="fab fa-google"></i> التقويم
                                            </a>
                                        @endif
                                        
                                        <form action="{{ route('activities.unregister', $activity->id) }}" method="POST"
                                            onsubmit="return confirm('هل تريد إلغاء تسجيلك في هذا النشاط؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition border border-red-100 font-bold" title="إلغاء التسجيل">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-10 flex justify-center">
                        @if(method_exists($activities, 'links'))
                            {{ $activities->links() }}
                        @endif
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-2xl shadow-sm border border-dashed border-gray-300 p-16 text-center">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-clipboard-list text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-black text-navy mb-2">لم تسجل في أي نشاط بعد</h3>
                        <p class="text-gray-500 mb-8 max-w-md mx-auto">ابدأ رحلتك الآن وتصفح الأنشطة المتاحة للتسجيل واكتساب النقاط!</p>
                        <a href="{{ route('activities.index') }}"
                            class="inline-block bg-gold text-navy px-8 py-3 rounded-xl font-bold hover:bg-yellow-600 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-search ml-2"></i> تصفح الأنشطة
                        </a>
                    </div>
                @endif

            </div>
        </main>
    </div>

</body>
</html>