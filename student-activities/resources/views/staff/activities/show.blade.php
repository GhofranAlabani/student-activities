<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $activity->title }} - تفاصيل النشاط | المشرف</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .bg-navy { background-color: #0a1929; }
        .text-gold { color: #d4a017; }
        .bg-gold { background-color: #d4a017; }
        .bg-gold:hover { background-color: #b8860b; }
    </style>
</head>
<body class="bg-[#f5f0e8] min-h-screen">

    <!-- Navbar -->
    <nav class="bg-navy text-white shadow-lg">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gold rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-shield text-navy"></i>
                </div>
                <div>
                    <h1 class="font-bold text-gold">لوحة المشرف</h1>
                    <p class="text-xs text-gray-300">{{ auth()->user()->name }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('staff.dashboard') }}" class="text-gray-300 hover:text-gold text-sm">
                    <i class="fas fa-home ml-1"></i> الرئيسية
                </a>
                <a href="{{ route('staff.activities.index') }}" class="text-gray-300 hover:text-gold text-sm">
                    <i class="fas fa-list ml-1"></i> أنشطتي
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-red-400 hover:text-red-300 text-sm">
                        <i class="fas fa-sign-out-alt ml-1"></i> خروج
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-8 max-w-5xl">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500 text-lg"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- زر رجوع -->
        <a href="{{ route('staff.activities.index') }}" class="inline-flex items-center gap-2 text-navy hover:text-gold mb-6 font-bold">
            <i class="fas fa-arrow-right"></i> رجوع للأنشطة
        </a>

        <!-- تفاصيل النشاط -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <!-- الصورة -->
            <div class="h-64 bg-gradient-to-br from-navy to-navy-light flex items-center justify-center relative">
                @if($activity->image)
                    <img src="{{ asset('storage/' . $activity->image) }}" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-calendar-alt text-8xl text-white/20"></i>
                @endif
                <div class="absolute top-4 right-4 flex gap-2">
                    <a href="{{ route('staff.activities.edit', $activity->id) }}" class="bg-gold text-navy px-4 py-2 rounded-lg font-bold hover:bg-yellow-600 transition text-sm">
                        <i class="fas fa-edit ml-1"></i> تعديل
                    </a>
                    <form action="{{ route('staff.activities.destroy', $activity->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا النشاط؟')">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-500 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-600 transition text-sm">
                            <i class="fas fa-trash ml-1"></i> حذف
                        </button>
                    </form>
                </div>
            </div>

            <!-- المحتوى -->
            <div class="p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="bg-navy/10 text-navy text-xs px-3 py-1 rounded-full font-bold">
                        {{ $activity->activityType->name ?? 'عام' }}
                    </span>
                    <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-bold">
                        {{ $activity->status ?? 'مفتوح' }}
                    </span>
                </div>

                <h1 class="text-3xl font-black text-navy mb-4">{{ $activity->title }}</h1>
                <p class="text-gray-600 text-lg mb-6 leading-relaxed">{{ $activity->description }}</p>

                <!-- معلومات النشاط -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gold/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar text-gold"></i>
                            </div>
                           <!-- التاريخ -->
<div>
    <label class="block text-navy font-bold mb-2">التاريخ *</label>
    <input type="date" name="date" 
           value="{{ old('date', $activity->date ? \Carbon\Carbon::parse($activity->date)->format('Y-m-d') : '') }}" 
           required
           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">
</div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-blue-500"></i>
                            </div>
                           <!-- الوقت -->
<div>
    <label class="block text-navy font-bold mb-2">الوقت *</label>
    <input type="time" name="time" 
           value="{{ old('time', $activity->time ? \Carbon\Carbon::parse($activity->time)->format('H:i') : '') }}" 
           required
           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">
</div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-green-500"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">المكان</p>
                                <p class="font-bold text-navy">{{ $activity->location ?? 'غير محدد' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-users text-purple-500"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">المسجلين</p>
                                <p class="font-bold text-navy">{{ $activity->registrations->count() }} / {{ $activity->max_participants ?? '∞' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gold/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-star text-gold"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">النقاط</p>
                                <p class="font-bold text-navy">{{ $activity->points ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-link text-red-500"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">رابط أونلاين</p>
                                <p class="font-bold text-navy">{{ $activity->online_link ? 'متاح' : 'غير متاح' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- قائمة المسجلين -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-black text-navy flex items-center gap-2">
                    <i class="fas fa-users text-gold"></i>
                    الطلاب المسجلين ({{ $activity->registrations->count() }})
                </h2>
                <a href="{{ route('staff.registrations.index', $activity->id) }}" class="bg-gold text-navy px-4 py-2 rounded-lg font-bold hover:bg-yellow-600 transition text-sm">
                    <i class="fas fa-list ml-1"></i> إدارة التسجيلات
                </a>
            </div>

            @if($activity->registrations->count() > 0)
                <div class="space-y-3">
                    @foreach($activity->registrations as $registration)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-navy rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr($registration->user->name ?? 'م', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-navy">{{ $registration->user->name ?? 'غير معروف' }}</p>
                                    <p class="text-xs text-gray-500">{{ $registration->user->email ?? '' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-500">
                                    {{ $registration->created_at->format('Y/m/d') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-user-slash text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">لا يوجد طلاب مسجلين بعد</p>
                </div>
            @endif
        </div>

    </div>

</body>
</html>