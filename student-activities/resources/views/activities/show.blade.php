<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $activity->title }} - تفاصيل النشاط</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body class="pb-12">

    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('activities.index') }}" class="text-2xl font-bold text-indigo-600 flex items-center gap-2">
                    <i class="fas fa-graduation-cap"></i>
                    نظام الأنشطة الطلابية
                </a>
                <div class="flex gap-4 items-center">
                    <a href="{{ route('activities.index') }}" class="text-gray-700 hover:text-indigo-600 font-semibold">الأنشطة</a>
                    @auth
                        <a href="/dashboard" class="text-gray-700 hover:text-indigo-600 font-semibold">لوحة التحكم</a>
                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @else
                        <a href="/login" class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition">تسجيل الدخول</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Image -->
    <div class="h-72 bg-gradient-to-r from-indigo-600 to-purple-600 relative overflow-hidden">
        @if($activity->image)
            <img src="{{ asset('storage/' . $activity->image) }}" alt="{{ $activity->title }}" class="w-full h-full object-cover opacity-80">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <i class="fas fa-calendar-alt text-9xl text-white/20"></i>
            </div>
        @endif
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        <!-- Back Button -->
        <div class="absolute top-4 right-4">
            <a href="{{ route('activities.index') }}" class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-xl hover:bg-white/30 transition font-semibold border border-white/30">
                <i class="fas fa-arrow-right ml-2"></i> العودة
            </a>
        </div>
        <!-- Admin Buttons -->
        @if(auth()->check() && auth()->user()->role === 'admin')
            <div class="absolute top-4 left-4 flex gap-2">
                <a href="{{ route('activities.edit', $activity->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-xl hover:bg-yellow-600 transition font-semibold">
                    <i class="fas fa-edit ml-1"></i> تعديل
                </a>
                <form action="{{ route('activities.destroy', $activity->id) }}" method="POST"
                    onsubmit="return confirm('هل أنت متأكد من حذف هذا النشاط؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-xl hover:bg-red-700 transition font-semibold">
                        <i class="fas fa-trash ml-1"></i> حذف
                    </button>
                </form>
            </div>
        @endif
        <!-- Title Overlay -->
        <div class="absolute bottom-6 right-6 left-6">
            <div class="flex items-center gap-3 mb-2">
                <span class="bg-white/20 backdrop-blur-sm text-white text-sm font-bold px-3 py-1 rounded-full border border-white/30">
                    {{ $activity->activityType->name ?? 'عام' }}
                </span>
                @if($activity->status === '?????')
                    <span class="bg-green-500 text-white text-sm font-bold px-3 py-1 rounded-full">
                        <i class="fas fa-check-circle ml-1"></i> متاح للتسجيل
                    </span>
                @endif
                @if($activity->points)
                    <span class="bg-yellow-500 text-white text-sm font-bold px-3 py-1 rounded-full">
                        <i class="fas fa-star ml-1"></i> {{ $activity->points }} نقطة
                    </span>
                @endif
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-white">{{ $activity->title }}</h1>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500 text-lg"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Quick Info Bar -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if($activity->date)
                            <div class="text-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-calendar text-indigo-600"></i>
                                </div>
                                <p class="text-xs text-gray-500">التاريخ</p>
                                <p class="font-bold text-gray-800 text-sm">{{ \Carbon\Carbon::parse($activity->date)->format('Y/m/d') }}</p>
                            </div>
                        @endif
                        @if($activity->time)
                            <div class="text-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-clock text-purple-600"></i>
                                </div>
                                <p class="text-xs text-gray-500">الوقت</p>
                                <p class="font-bold text-gray-800 text-sm">{{ $activity->time }}</p>
                            </div>
                        @endif
                        @if($activity->location)
                            <div class="text-center">
                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-map-marker-alt text-red-500"></i>
                                </div>
                                <p class="text-xs text-gray-500">المكان</p>
                                <p class="font-bold text-gray-800 text-sm truncate">{{ $activity->location }}</p>
                            </div>
                        @endif
                        @if($activity->max_participants)
                            <div class="text-center">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-users text-green-600"></i>
                                </div>
                                <p class="text-xs text-gray-500">المشاركون</p>
                                <p class="font-bold text-gray-800 text-sm">{{ $activity->users->count() }} / {{ $activity->max_participants }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-indigo-600"></i>
                        عن النشاط
                    </h3>
                    <p class="text-gray-600 leading-relaxed text-lg">{{ $activity->description }}</p>

                    <!-- Points & Certificate -->
                    @if($activity->points || $activity->certificate)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                            @if($activity->points)
                                <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-xl flex items-center gap-3">
                                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-star text-yellow-500 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">النقاط المكتسبة</p>
                                        <p class="text-2xl font-extrabold text-gray-800">{{ $activity->points }} نقطة</p>
                                    </div>
                                </div>
                            @endif
                            @if($activity->certificate)
                                <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl flex items-center gap-3">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-certificate text-blue-500 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">الشهادة</p>
                                        <p class="text-xl font-bold text-gray-800">شهادة حضور</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($activity->online_link)
                        <div class="mt-4 bg-green-50 border border-green-200 p-4 rounded-xl flex items-center gap-3">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-video text-green-500 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">رابط الحضور أونلاين</p>
                                <a href="{{ $activity->online_link }}" target="_blank" class="text-indigo-600 font-bold hover:underline">
                                    انضم عبر الإنترنت
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Registered Users -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2">
                        <i class="fas fa-users text-indigo-600"></i>
                        المسجلون في النشاط
                        <span class="bg-indigo-100 text-indigo-700 text-sm px-3 py-1 rounded-full font-bold mr-auto">
                            {{ $activity->users->count() }} / {{ $activity->max_participants ?? '∞' }}
                        </span>
                    </h3>

                    @if($activity->max_participants)
                        @php $percentage = ($activity->users->count() / $activity->max_participants) * 100; @endphp
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-5">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                        </div>
                    @endif

                    @if($activity->users->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($activity->users->take(6) as $user)
                                <div class="flex items-center p-3 bg-gray-50 rounded-xl border border-gray-100">
                                    <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold ml-3">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($activity->users->count() > 6)
                            <p class="text-center text-gray-500 mt-4 text-sm">
                                و {{ $activity->users->count() - 6 }} مشاركين آخرين...
                            </p>
                        @endif
                    @else
                        <div class="text-center py-10 text-gray-400">
                            <i class="fas fa-user-slash text-5xl mb-3"></i>
                            <p class="font-semibold">لا يوجد مسجلين حتى الآن</p>
                            <p class="text-sm mt-1">كن أول من يسجل!</p>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Registration Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2">
                        <i class="fas fa-clipboard-list text-indigo-600"></i>
                        التسجيل في النشاط
                    </h3>

                    @auth
                        @if(auth()->user()->role === 'admin')
                            <div class="bg-purple-50 border border-purple-200 text-purple-700 px-4 py-3 rounded-xl text-center font-bold">
                                <i class="fas fa-user-shield ml-2"></i>
                                أنت مدير النظام
                            </div>
                            <a href="{{ route('admin.registrations', $activity->id) }}" class="block w-full bg-green-600 text-white text-center py-3 rounded-xl hover:bg-green-700 transition font-bold mt-3">
                                <i class="fas fa-users ml-2"></i>
                                عرض المسجلين
                            </a>
                        @elseif($activity->users->contains(auth()->id()))
                            <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-4 rounded-xl text-center">
                                <i class="fas fa-check-circle text-green-500 text-3xl mb-2 block"></i>
                                <p class="font-bold text-lg">أنت مسجل في هذا النشاط</p>
                                <p class="text-sm text-green-600 mt-1">نراك قريباً!</p>
                            </div>
                            <form action="{{ route('activities.unregister', $activity->id) }}" method="POST" class="mt-3"
                                onsubmit="return confirm('هل تريد إلغاء تسجيلك؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-50 text-red-600 border border-red-200 py-2.5 rounded-xl hover:bg-red-100 transition font-semibold text-sm">
                                    <i class="fas fa-times ml-1"></i> إلغاء التسجيل
                                </button>
                            </form>
                        @elseif($activity->users->count() >= ($activity->max_participants ?? PHP_INT_MAX))
                            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-4 rounded-xl text-center">
                                <i class="fas fa-times-circle text-red-500 text-3xl mb-2 block"></i>
                                <p class="font-bold">اكتمل العدد</p>
                                <p class="text-sm mt-1">لا توجد أماكن متاحة</p>
                            </div>
                        @elseif($activity->status !== '?????')
                            <div class="bg-gray-50 border border-gray-200 text-gray-600 px-4 py-4 rounded-xl text-center">
                                <i class="fas fa-ban text-gray-400 text-3xl mb-2 block"></i>
                                <p class="font-bold">التسجيل مغلق</p>
                            </div>
                        @else
                            <form action="{{ route('activities.register', $activity->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-xl hover:bg-indigo-700 transition font-extrabold text-lg shadow-lg hover:shadow-indigo-200">
                                    <i class="fas fa-check ml-2"></i>
                                    سجل الآن
                                </button>
                            </form>
                        @endif
                    @else
                        <p class="text-gray-500 text-sm mb-4 text-center">سجّل دخولك للمشاركة في هذا النشاط</p>
                        <a href="/login" class="block w-full bg-indigo-600 text-white text-center py-4 rounded-xl hover:bg-indigo-700 transition font-extrabold text-lg">
                            <i class="fas fa-sign-in-alt ml-2"></i>
                            تسجيل الدخول
                        </a>
                        <a href="{{ route('register') }}" class="block w-full bg-white border-2 border-indigo-600 text-indigo-600 text-center py-3 rounded-xl hover:bg-indigo-50 transition font-bold mt-3">
                            إنشاء حساب جديد
                        </a>
                    @endauth

                    <!-- Favorite Button -->
                    @auth
                        <form action="{{ route('activities.favorite', $activity->id) }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="w-full bg-pink-50 text-pink-600 border border-pink-200 py-2.5 rounded-xl hover:bg-pink-100 transition font-semibold">
                                <i class="fas fa-heart ml-1"></i>
                                إضافة للمفضلة
                            </button>
                        </form>
                    @endauth
                </div>

                <!-- Organizer -->
                @if($activity->creator)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-user-tie text-indigo-600"></i>
                            المنظم
                        </h3>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($activity->creator->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">{{ $activity->creator->name }}</p>
                                <p class="text-sm text-gray-500">{{ $activity->creator->email }}</p>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

</body>
</html>
