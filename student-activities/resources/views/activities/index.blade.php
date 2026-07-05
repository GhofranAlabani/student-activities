<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الأنشطة الطلابية</title>
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
        
        .activity-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .activity-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(10, 25, 41, 0.15); }
        
        [x-cloak] { display: none !important; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> 
</head>
<body class="bg-[#f5f0e8] min-h-screen pb-12">

    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50 border-b border-gray-100">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="text-2xl font-black text-navy flex items-center gap-2">
                    <i class="fas fa-graduation-cap text-gold"></i>
                    نظام الأنشطة الطلابية
                </a>
              <div class="flex gap-4 items-center">
                <a href="{{ route('activities.index') }}" class="text-gray-700 hover:text-gold font-bold transition hidden md:block">الأنشطة</a>
                
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('student.dashboard') }}" class="text-gray-700 hover:text-gold font-bold transition hidden md:block">لوحة التحكم</a>

                    <!-- جرس الإشعارات -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative text-gray-600 hover:text-gold transition p-2 focus:outline-none">
                            <i class="fas fa-bell text-xl"></i>
                            @php
                                $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center animate-pulse border-2 border-white">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </button>

                        <div x-show="open" @click.outside="open = false" x-cloak
                            class="absolute left-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden origin-top-left">
                            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                <h3 class="font-bold text-navy">الإشعارات</h3>
                                <a href="{{ route('notifications.index') }}" class="text-xs text-gold hover:text-yellow-700 font-bold">عرض الكل</a>
                            </div>
                            <div class="max-h-96 overflow-y-auto custom-scrollbar">
                                @php
                                    $recentNotifications = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(5)->get();
                                @endphp
                                @forelse($recentNotifications as $notif)
                                    <a href="{{ route('notifications.read', $notif->id) }}" class="block p-4 border-b border-gray-50 hover:bg-gray-50 transition {{ !$notif->is_read ? 'bg-blue-50/30' : '' }}">
                                        <div class="flex items-start gap-3">
                                            <div class="w-9 h-9 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-{{ $notif->icon ?? 'bell' }} text-indigo-600 text-sm"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-800 text-sm">{{ $notif->title }}</p>
                                                <p class="text-gray-500 text-xs mt-0.5 line-clamp-2">{{ $notif->message }}</p>
                                                <p class="text-gray-400 text-xs mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                            </div>
                                            @if(!$notif->is_read)
                                                <span class="w-2 h-2 bg-gold rounded-full mt-1.5 flex-shrink-0"></span>
                                            @endif
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-8 text-center text-gray-400">
                                        <i class="fas fa-bell-slash text-3xl mb-2"></i>
                                        <p class="text-sm">لا توجد إشعارات</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="w-9 h-9 bg-gold rounded-full flex items-center justify-center text-navy font-bold shadow-md">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                @else
                    <a href="{{ route('login') }}" class="bg-navy text-white px-5 py-2 rounded-lg hover:bg-gold hover:text-navy transition font-bold">تسجيل الدخول</a>
                @endauth
            </div>
            </div>
        </div>
    </nav>

    <!-- Header & Filters -->
    <div class="bg-gradient-to-br from-navy to-navy-light py-12 mb-8 relative overflow-hidden">
        <!-- زخرفة خلفية -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-gold/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="flex justify-between items-center mb-8 flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <!-- زر الرجوع -->
                    <a href="{{ route('student.dashboard') }}" 
                       class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 text-white px-6 py-3 rounded-xl hover:bg-white hover:text-navy transition font-bold shadow-lg">
                        <i class="fas fa-arrow-right text-lg"></i>
                        <span>رجوع</span>
                    </a>
                    
                    <h2 class="text-3xl md:text-4xl font-black text-white drop-shadow-lg">
                        اكتشف الأنشطة <span class="text-gold">المتاحة</span>
                    </h2>
                </div>
                
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('activities.create') }}" class="bg-gold text-navy px-6 py-3 rounded-xl font-bold hover:bg-yellow-500 transition shadow-lg flex items-center gap-2">
                        <i class="fas fa-plus"></i> إضافة نشاط
                    </a>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-2xl p-5 max-w-4xl mx-auto border border-gray-100">
                <form method="GET" action="{{ route('activities.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="ابحث عن نشاط..."
                            class="w-full pr-12 pl-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold transition">
                        <i class="fas fa-search absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <div class="md:w-56 relative">
                        <select name="type" class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold appearance-none cursor-pointer transition">
                            <option value="">جميع الأنواع</option>
                            @foreach(\App\Models\ActivityType::all() as $type)
                                <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                    <button type="submit" class="bg-navy text-white px-8 py-3.5 rounded-xl hover:bg-gold hover:text-navy transition font-bold shadow-md flex items-center justify-center gap-2">
                        <i class="fas fa-filter"></i> فلترة
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Activities Grid -->
    <div class="container mx-auto px-4">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
                <i class="fas fa-check-circle text-green-500 text-lg"></i> {{ session('success') }}
            </div>
        @endif

        @if($activities->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($activities as $activity)
                    <div class="activity-card bg-white rounded-2xl shadow-lg overflow-hidden relative border border-gray-100 flex flex-col h-full">

                        <!-- زر المفضلة -->
                        @auth
                            <div class="absolute top-3 left-3 z-20">
                                <form action="{{ route('activities.favorite', $activity->id) }}" method="POST" class="m-0 p-0">
                                    @csrf
                                    <button type="submit" class="bg-white/90 backdrop-blur-sm p-2.5 rounded-full shadow-md hover:bg-white transition transform hover:scale-110 focus:outline-none group">
                                        @if(in_array($activity->id, $favoriteIds ?? []))
                                            <i class="fas fa-heart text-red-500 text-lg group-hover:text-red-600 animate-pulse"></i>
                                        @else
                                            <i class="far fa-heart text-gray-400 text-lg group-hover:text-red-400"></i>
                                        @endif
                                    </button>
                                </form>
                            </div>
                        @endauth

                        <!-- صورة النشاط -->
                        <div class="h-48 bg-gradient-to-br from-navy to-navy-light flex items-center justify-center relative overflow-hidden">
                            @if($activity->image)
                                <img src="{{ asset('storage/' . $activity->image) }}" alt="{{ $activity->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <i class="fas fa-calendar-alt text-6xl text-white/10"></i>
                            @endif
                            
                            <!-- حالة النشاط -->
                            @if($activity->status === 'مفتوح' || $activity->status === 'active')
                                <span class="absolute top-3 right-3 bg-emerald-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1">
                                    <i class="fas fa-check-circle"></i> متاح
                                </span>
                            @endif
                        </div>

                        <!-- محتوى البطاقة -->
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <span class="bg-navy/10 text-navy text-xs font-bold px-3 py-1.5 rounded-full border border-navy/5">
                                    {{ $activity->activityType->name ?? 'عام' }}
                                </span>
                                @if($activity->points)
                                    <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1.5 rounded-full border border-yellow-200 flex items-center gap-1">
                                        <i class="fas fa-star text-yellow-500"></i> {{ $activity->points }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-xl font-black text-navy mb-3 line-clamp-1 group-hover:text-gold transition">
                                {{ $activity->title }}
                            </h3>

                            <p class="text-gray-500 text-sm mb-5 line-clamp-2 h-10 leading-relaxed">
                                {{ \Illuminate\Support\Str::limit($activity->description, 100) }}
                            </p>

                            <div class="space-y-2.5 mb-6 text-sm text-gray-600 bg-gray-50 p-4 rounded-xl border border-gray-100 flex-1">
                                @if($activity->location)
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-map-marker-alt text-gold w-5 text-center"></i>
                                        <span class="truncate font-medium">{{ $activity->location }}</span>
                                    </div>
                                @endif
                                @if($activity->date)
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-calendar text-gold w-5 text-center"></i>
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($activity->date)->format('Y/m/d') }}</span>
                                    </div>
                                @endif
                                @if($activity->max_participants)
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-users text-gold w-5 text-center"></i>
                                        <span class="font-medium">{{ $activity->users->count() }} / {{ $activity->max_participants }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col gap-3 mt-auto">
                                <!-- زر التفاصيل -->
                                <a href="{{ route('activities.show', $activity->id) }}" class="w-full bg-navy text-white text-center py-3 rounded-xl hover:bg-gold hover:text-navy transition font-bold shadow-md text-sm flex items-center justify-center gap-2">
                                    <i class="fas fa-eye"></i> التفاصيل
                                </a>

                                @if(auth()->check() && auth()->user()->role === 'admin')
                                    <!-- زر عرض المسجلين/طلبات الانضمام -->
                                    @if($activity->requires_approval ?? false)
                                        @php
                                            $pendingCount = $activity->registrations()->where('status', 'pending')->count();
                                        @endphp
                                        <a href="{{ route('admin.registrations', $activity->id) }}" 
                                           class="w-full bg-amber-600 text-white text-center py-2.5 rounded-xl hover:bg-amber-700 transition font-bold shadow-sm text-sm flex items-center justify-center gap-2 relative">
                                            <i class="fas fa-clock"></i> 
                                            طلبات الانضمام
                                            @if($pendingCount > 0)
                                                <span class="absolute -top-2 -left-2 bg-red-500 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center border-2 border-white animate-pulse">
                                                    {{ $pendingCount }}
                                                </span>
                                            @endif
                                        </a>
                                    @else
                                        <a href="{{ route('admin.registrations', $activity->id) }}" 
                                           class="w-full bg-emerald-600 text-white text-center py-2.5 rounded-xl hover:bg-emerald-700 transition font-bold shadow-sm text-sm flex items-center justify-center gap-2">
                                            <i class="fas fa-users"></i> 
                                            عرض المسجلين ({{ $activity->users->count() }})
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('activities.edit', $activity->id) }}" class="w-full bg-gold text-navy text-center py-2.5 rounded-xl hover:bg-yellow-500 transition font-bold shadow-sm text-sm flex items-center justify-center gap-2">
                                        <i class="fas fa-edit"></i> تعديل
                                    </a>
                                    <form action="{{ route('activities.destroy', $activity->id) }}" method="POST"
                                        onsubmit="return confirm('هل أنت متأكد من حذف هذا النشاط؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-red-600 text-white py-2.5 rounded-xl hover:bg-red-700 transition font-bold shadow-sm text-sm flex items-center justify-center gap-2">
                                            <i class="fas fa-trash"></i> حذف
                                        </button>
                                    </form>
                                @endif

                                @auth
                                   @if(in_array($activity->id, $registeredIds ?? []))
                                    <div class="w-full bg-green-100 text-green-700 text-center py-2.5 rounded-xl font-bold text-sm border border-green-200 flex items-center justify-center gap-2">
                                        <i class="fas fa-check-circle"></i> تم التسجيل
                                    </div>
                                @else
                                    @if($activity->status === 'مفتوح' || $activity->status === 'active')
                                        <form action="{{ route('activities.register', $activity->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full bg-emerald-600 text-white py-3 rounded-xl hover:bg-emerald-700 transition font-bold shadow-md text-sm flex items-center justify-center gap-2">
                                                <i class="fas fa-check"></i> سجل الآن
                                            </button>
                                        </form>
                                    @endif
                                @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                {{ $activities->withQueryString()->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center max-w-md mx-auto mt-8 border border-dashed border-gray-300">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-black text-navy mb-2">لا توجد أنشطة مطابقة</h3>
                <p class="text-gray-500 mb-6">جرب تغيير معايير البحث أو إضافة أنشطة جديدة من لوحة التحكم.</p>
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('activities.create') }}" class="inline-block bg-navy text-white px-6 py-3 rounded-xl font-bold hover:bg-gold hover:text-navy transition shadow-lg">
                        <i class="fas fa-plus ml-2"></i> إضافة نشاط جديد
                    </a>
                @endif
            </div>
        @endif
    </div>

</body>
</html>