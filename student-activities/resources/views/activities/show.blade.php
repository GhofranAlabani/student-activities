<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $activity->title }} - تفاصيل النشاط</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="pb-12">

    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('activities.index') }}" class="text-2xl font-bold text-indigo-600">
                    <i class="fas fa-arrow-right ml-2"></i>
                    نظام الأنشطة الطلابية
                </a>
                <div class="flex gap-4">
                    <a href="{{ route('activities.index') }}" class="text-gray-700 hover:text-indigo-600 font-semibold">الأنشطة</a>
                    @auth
                        <a href="/dashboard" class="text-gray-700 hover:text-indigo-600 font-semibold">لوحة التحكم</a>
                    @else
                        <a href="/login" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">تسجيل الدخول</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        
        <!-- Back Button -->
        <a href="{{ route('activities.index') }}" class="inline-flex items-center text-white mb-6 hover:underline">
            <i class="fas fa-arrow-right ml-2"></i>
            العودة للأنشطة
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Main Info (2/3 width) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Activity Image & Title -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-64 bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                        @if($activity->image)
                            <img src="{{ asset('storage/' . $activity->image) }}" alt="{{ $activity->title }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-calendar-alt text-8xl text-white opacity-50"></i>
                        @endif
                    </div>
                    
                    <div class="p-6">
                        <!-- Type Badge -->
                        <div class="mb-4">
                            <span class="bg-indigo-100 text-indigo-800 text-sm font-semibold px-4 py-2 rounded-full">
                                {{ $activity->activityType->name ?? 'بدون نوع' }}
                            </span>
                            @if($activity->status === 'active')
                                <span class="bg-green-100 text-green-800 text-sm font-semibold px-4 py-2 rounded-full mr-2">
                                    <i class="fas fa-check-circle ml-1"></i>
                                    متاح للتسجيل
                                </span>
                            @endif
                        </div>

                        <!-- Title -->
                        <h1 class="text-3xl font-bold text-gray-800 mb-4">
                            {{ $activity->title }}
                        </h1>

                        <!-- Description -->
                        <div class="prose max-w-none">
                            <h3 class="text-xl font-bold text-gray-700 mb-3">
                                <i class="fas fa-info-circle ml-2 text-indigo-600"></i>
                                عن النشاط
                            </h3>
                            <p class="text-gray-600 leading-relaxed mb-6">
                                {{ $activity->description }}
                            </p>
                        </div>

                        <!-- Additional Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                            @if($activity->points)
                                <div class="bg-yellow-50 border-r-4 border-yellow-500 p-4 rounded">
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-500 text-2xl ml-3"></i>
                                        <div>
                                            <p class="text-sm text-gray-600">النقاط المكتسبة</p>
                                            <p class="text-xl font-bold text-gray-800">{{ $activity->points }} نقطة</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($activity->certificate)
                                <div class="bg-blue-50 border-r-4 border-blue-500 p-4 rounded">
                                    <div class="flex items-center">
                                        <i class="fas fa-certificate text-blue-500 text-2xl ml-3"></i>
                                        <div>
                                            <p class="text-sm text-gray-600">الشهادة</p>
                                            <p class="text-lg font-bold text-gray-800">شهادة حضور</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Registered Users Section -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-users ml-2 text-indigo-600"></i>
                        المسجلون في النشاط
                        <span class="text-sm font-normal text-gray-500 mr-2">
                            ({{ $activity->users->count() }} / {{ $activity->max_participants ?? '∞' }})
                        </span>
                    </h3>

                    @if($activity->users->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($activity->users->take(6) as $user)
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
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
                            <p class="text-center text-gray-500 mt-4">
                                و {{ $activity->users->count() - 6 }} مشاركين آخرين...
                            </p>
                        @endif
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-user-slash text-4xl mb-3"></i>
                            <p>لا يوجد مسجلين حتى الآن</p>
                            <p class="text-sm">كن أول من يسجل!</p>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Sidebar (1/3 width) -->
            <div class="space-y-6">
                
                <!-- Registration Card -->
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-clipboard-list ml-2 text-indigo-600"></i>
                        معلومات التسجيل
                    </h3>

                    <!-- Date & Time -->
                    <div class="space-y-3 mb-6">
                        @if($activity->date)
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-calendar ml-3 text-indigo-600 w-6"></i>
                                <span>
                                    {{ \Carbon\Carbon::parse($activity->date)->format('Y/m/d') }}
                                </span>
                            </div>
                        @endif

                        @if($activity->time)
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-clock ml-3 text-indigo-600 w-6"></i>
                                <span>
                                    من {{ $activity->time }} إلى {{ $activity->end_time ?? 'غير محدد' }}
                                </span>
                            </div>
                        @endif

                        @if($activity->location)
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-map-marker-alt ml-3 text-red-500 w-6"></i>
                                <span>{{ $activity->location }}</span>
                            </div>
                        @endif

                        @if($activity->online_link)
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-video ml-3 text-green-500 w-6"></i>
                                <a href="{{ $activity->online_link }}" target="_blank" class="text-indigo-600 hover:underline">
                                    رابط الحضور أونلاين
                                </a>
                            </div>
                        @endif

                        @if($activity->max_participants)
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-users ml-3 text-green-500 w-6"></i>
                                <span>
                                    {{ $activity->users->count() }} / {{ $activity->max_participants }} مشارك
                                </span>
                            </div>
                            
                            <!-- Progress Bar -->
                            @php
                                $percentage = ($activity->users->count() / $activity->max_participants) * 100;
                            @endphp
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        @endif
                    </div>

                    <!-- Register Button -->
                    @auth
                        @if($activity->users->contains(auth()->id()))
                            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded text-center">
                                <i class="fas fa-check-circle ml-2"></i>
                                أنت مسجل في هذا النشاط
                            </div>
                        @elseif($activity->users->count() >= ($activity->max_participants ?? PHP_INT_MAX))
                            <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded text-center">
                                <i class="fas fa-times-circle ml-2"></i>
                                اكتمل العدد
                            </div>
                        @else
                            <form action="{{ route('activities.register', $activity->id) }}" method="POST">
                                @csrf
                                <button 
                                    type="submit" 
                                    class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition font-bold text-lg shadow-lg"
                                >
                                    <i class="fas fa-check ml-2"></i>
                                    سجل الآن في النشاط
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="/login" class="block w-full bg-indigo-600 text-white text-center py-3 rounded-lg hover:bg-indigo-700 transition font-bold text-lg">
                            <i class="fas fa-sign-in-alt ml-2"></i>
                            تسجيل الدخول للتسجيل
                        </a>
                    @endauth
                </div>

                <!-- Contact/Organizer Info -->
                @if($activity->creator)
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">
                            <i class="fas fa-user-tie ml-2 text-indigo-600"></i>
                            المنظم
                        </h3>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold ml-3">
                                {{ substr($activity->creator->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $activity->creator->name }}</p>
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