<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الأنشطة الطلابية</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .activity-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .activity-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.15); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen pb-12">

    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="text-2xl font-bold text-indigo-600 flex items-center gap-2">
                    <i class="fas fa-graduation-cap"></i>
                    نظام الأنشطة الطلابية
                </a>
                <div class="flex gap-4 items-center">
                    <a href="{{ route('activities.index') }}" class="text-gray-700 hover:text-indigo-600 font-semibold">الأنشطة</a>
                    @auth
                        <a href="{{ route('student.dashboard') }}" class="text-gray-700 hover:text-indigo-600 font-semibold">لوحة التحكم</a>
                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition">تسجيل الدخول</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Header & Filters -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 py-10 mb-8">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8 flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <!-- زر الرجوع -->
                    <a href="{{ url()->previous() }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition backdrop-blur-sm">
                        <i class="fas fa-arrow-right"></i>
                        <span class="font-semibold">رجوع</span>
                    </a>
                    <h2 class="text-3xl md:text-4xl font-bold text-white">
                        اكتشف الأنشطة المتاحة
                    </h2>
                </div>
                
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('activities.create') }}" class="bg-white text-indigo-600 px-6 py-3 rounded-xl font-bold hover:bg-indigo-50 transition shadow-lg">
                        <i class="fas fa-plus ml-2"></i> إضافة نشاط
                    </a>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-xl p-4 max-w-4xl mx-auto">
                <form method="GET" action="{{ route('activities.index') }}" class="flex flex-col md:flex-row gap-3">
                    <div class="flex-1 relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="ابحث عن نشاط..."
                            class="w-full pr-10 pl-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <div class="md:w-48">
                        <select name="type" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                            <option value="">جميع الأنواع</option>
                            @foreach(\App\Models\ActivityType::all() as $type)
                                <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition font-semibold shadow-md">
                        <i class="fas fa-filter ml-2"></i> فلترة
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Activities Grid -->
    <div class="container mx-auto px-4">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
            </div>
        @endif

        @if($activities->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($activities as $activity)
                    <div class="activity-card bg-white rounded-xl shadow-lg overflow-hidden relative border border-gray-100">

                        <!-- زر المفضلة -->
                        @auth
                            <div class="absolute top-3 left-3 z-20">
                                <form action="{{ route('activities.favorite', $activity->id) }}" method="POST" class="m-0 p-0">
                                    @csrf
                                    <button type="submit" class="bg-white/90 backdrop-blur-sm p-2 rounded-full shadow-md hover:bg-white transition transform hover:scale-110 focus:outline-none group">
                                        @if(in_array($activity->id, $favoriteIds ?? []))
                                            <i class="fas fa-heart text-red-500 text-lg group-hover:text-red-600"></i>
                                        @else
                                            <i class="far fa-heart text-gray-400 text-lg group-hover:text-red-400"></i>
                                        @endif
                                    </button>
                                </form>
                            </div>
                        @endauth

                        <!-- صورة النشاط -->
                        <div class="h-48 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center relative overflow-hidden">
                            @if($activity->image)
                                <img src="{{ asset('storage/' . $activity->image) }}" alt="{{ $activity->title }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-calendar-alt text-6xl text-white/20"></i>
                            @endif
                            
                            <!-- ✅ التصحيح: استبدل ????? بـ active -->
                            @if($activity->status === '?????')
                                <span class="absolute top-3 right-3 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-sm">
                                    متاح للتسجيل
                                </span>
                            @endif
                        </div>

                        <!-- محتوى البطاقة -->
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-3">
                                <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full border border-indigo-100">
                                    {{ $activity->activityType->name ?? 'عام' }}
                                </span>
                                @if($activity->points)
                                    <span class="bg-yellow-50 text-yellow-700 text-xs font-bold px-2 py-1 rounded-full border border-yellow-100 flex items-center gap-1">
                                        <i class="fas fa-star text-yellow-500"></i> {{ $activity->points }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-1">
                                {{ $activity->title }}
                            </h3>

                            <p class="text-gray-500 text-sm mb-4 line-clamp-2 h-10">
                                {{ \Illuminate\Support\Str::limit($activity->description, 100) }}
                            </p>

                            <div class="space-y-2 mb-5 text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">
                                @if($activity->location)
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-map-marker-alt text-red-400 w-4"></i>
                                        <span class="truncate">{{ $activity->location }}</span>
                                    </div>
                                @endif
                                @if($activity->date)
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-calendar text-blue-400 w-4"></i>
                                        <span>{{ \Carbon\Carbon::parse($activity->date)->format('Y/m/d') }}</span>
                                    </div>
                                @endif
                                @if($activity->max_participants)
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-users text-green-400 w-4"></i>
                                        <span>{{ $activity->users->count() }} / {{ $activity->max_participants }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col gap-2">
                                <!-- زر التفاصيل -->
                                <a href="{{ route('activities.show', $activity->id) }}" class="w-full bg-indigo-600 text-white text-center py-2.5 rounded-lg hover:bg-indigo-700 transition font-semibold shadow-sm text-sm">
                                    <i class="fas fa-eye ml-1"></i> التفاصيل
                                </a>

                                @if(auth()->check() && auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.registrations', $activity->id) }}" class="w-full bg-green-600 text-white text-center py-2.5 rounded-lg hover:bg-green-700 transition font-semibold shadow-sm text-sm">
                                        <i class="fas fa-users ml-1"></i> عرض المسجلين
                                    </a>
                                    <a href="{{ route('activities.edit', $activity->id) }}" class="w-full bg-yellow-500 text-white text-center py-2.5 rounded-lg hover:bg-yellow-600 transition font-semibold shadow-sm text-sm">
                                        <i class="fas fa-edit ml-1"></i> تعديل
                                    </a>
                                    <form action="{{ route('activities.destroy', $activity->id) }}" method="POST"
                                        onsubmit="return confirm('هل أنت متأكد من حذف هذا النشاط؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-red-600 text-white py-2.5 rounded-lg hover:bg-red-700 transition font-semibold shadow-sm text-sm">
                                            <i class="fas fa-trash ml-1"></i> حذف
                                        </button>
                                    </form>
                                @endif

                                @auth
                                   @if($activity->users->contains(auth()->id()))
    <div class="w-full bg-green-100 text-green-700 text-center py-2.5 rounded-lg font-semibold text-sm border border-green-300">
        <i class="fas fa-check-circle ml-1"></i> تم التسجيل
    </div>
@else
    <form action="{{ route('activities.register', $activity->id) }}" method="POST">
        @csrf
        <button type="submit" class="w-full bg-emerald-600 text-white py-2.5 rounded-lg hover:bg-emerald-700 transition font-semibold shadow-sm text-sm">
            <i class="fas fa-check ml-1"></i> سجل الآن
        </button>
    </form>
@endif
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                {{ $activities->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl shadow-lg p-12 text-center max-w-md mx-auto mt-8">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-700 mb-2">لا توجد أنشطة مطابقة</h3>
                <p class="text-gray-500">جرب تغيير معايير البحث أو إضافة أنشطة جديدة من لوحة التحكم.</p>
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('activities.create') }}" class="inline-block mt-4 bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-indigo-700 transition">
                        <i class="fas fa-plus ml-2"></i> إضافة نشاط جديد
                    </a>
                @endif
            </div>
        @endif
    </div>

</body>
</html>
