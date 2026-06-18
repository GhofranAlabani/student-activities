<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $activity->title }} - تفاصيل النشاط</title>
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
        
        /* تنسيق التقييم بالنجوم */
        .star-rating input { display: none; }
        .star-rating label { cursor: pointer; transition: color 0.2s; }
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label { color: #d4a017 !important; }
    </style>
</head>
<body class="bg-[#f5f0e8] pb-12">

    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50 border-b border-gray-100">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('activities.index') }}" class="text-2xl font-black text-navy flex items-center gap-2">
                    <i class="fas fa-graduation-cap text-gold"></i> نظام الأنشطة الطلابية
                </a>
                <div class="flex gap-4 items-center">
                    <a href="{{ route('activities.index') }}" class="text-gray-600 hover:text-gold font-bold transition hidden md:block">الأنشطة</a>
                    @auth
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('student.dashboard') }}" class="text-gray-600 hover:text-gold font-bold transition hidden md:block">لوحة التحكم</a>
                        <div class="w-9 h-9 bg-gold rounded-full flex items-center justify-center text-navy font-bold shadow-md">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @else
                        <a href="/login" class="bg-navy text-white px-5 py-2 rounded-lg hover:bg-gold hover:text-navy transition font-bold">تسجيل الدخول</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="h-80 md:h-96 bg-gradient-to-br from-navy to-navy-light relative overflow-hidden">
        @if($activity->image)
            <img src="{{ asset('storage/' . $activity->image) }}" alt="{{ $activity->title }}" class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-overlay">
        @else
            <div class="absolute inset-0 flex items-center justify-center opacity-10">
                <i class="fas fa-calendar-alt text-9xl text-white"></i>
            </div>
        @endif
        
        <!-- Back Button -->
        <div class="absolute top-6 right-6 z-10">
            <a href="{{ route('activities.index') }}" class="bg-white/10 backdrop-blur-md border border-white/20 text-white px-5 py-2 rounded-xl hover:bg-white hover:text-navy transition font-bold flex items-center gap-2">
                <i class="fas fa-arrow-right"></i> العودة
            </a>
        </div>
        
        <!-- Admin Buttons -->
        @if(auth()->check() && auth()->user()->role === 'admin')
            <div class="absolute top-6 left-6 flex gap-2 z-10">
                <a href="{{ route('activities.edit', $activity->id) }}" class="bg-gold text-navy px-4 py-2 rounded-xl hover:bg-yellow-500 transition font-bold shadow-lg">
                    <i class="fas fa-edit ml-1"></i> تعديل
                </a>
                <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا النشاط؟')">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-xl hover:bg-red-700 transition font-bold shadow-lg">
                        <i class="fas fa-trash ml-1"></i> حذف
                    </button>
                </form>
            </div>
        @endif
        
        <!-- Title Overlay -->
        <div class="absolute bottom-0 left-0 right-0 p-8 bg-gradient-to-t from-navy/95 to-transparent">
            <div class="container mx-auto max-w-6xl">
                <div class="flex flex-wrap gap-3 mb-4">
                    <span class="bg-gold text-navy px-4 py-1.5 rounded-full text-sm font-black shadow-lg">
                        {{ $activity->activityType->name ?? 'عام' }}
                    </span>
                    @if($activity->status === 'مفتوح')
                        <span class="bg-emerald-500 text-white px-4 py-1.5 rounded-full text-sm font-bold shadow-lg flex items-center gap-1">
                            <i class="fas fa-check-circle"></i> متاح للتسجيل
                        </span>
                    @endif
                    @if($activity->points)
                        <span class="bg-yellow-400 text-navy px-4 py-1.5 rounded-full text-sm font-black shadow-lg flex items-center gap-1">
                            <i class="fas fa-star"></i> {{ $activity->points }} نقطة
                        </span>
                    @endif
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-white leading-tight drop-shadow-lg">{{ $activity->title }}</h1>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-10 max-w-6xl -mt-10 relative z-10">
        
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
                <i class="fas fa-check-circle text-green-500 text-lg"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
                <i class="fas fa-exclamation-circle text-red-500 text-lg"></i> {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Right Column: Details -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Quick Info Bar -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-t-4 border-gold">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        @if($activity->date)
                            <div class="text-center p-3 bg-gray-50 rounded-xl">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-calendar text-blue-600 text-xl"></i>
                                </div>
                                <p class="text-xs text-gray-500 font-bold uppercase">التاريخ</p>
                                <p class="font-black text-navy text-lg">{{ \Carbon\Carbon::parse($activity->date)->format('Y/m/d') }}</p>
                            </div>
                        @endif
                        @if($activity->time)
                            <div class="text-center p-3 bg-gray-50 rounded-xl">
                                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-clock text-purple-600 text-xl"></i>
                                </div>
                                <p class="text-xs text-gray-500 font-bold uppercase">الوقت</p>
                                <p class="font-black text-navy text-lg">{{ $activity->time }}</p>
                            </div>
                        @endif
                        @if($activity->location)
                            <div class="text-center p-3 bg-gray-50 rounded-xl">
                                <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-map-marker-alt text-emerald-600 text-xl"></i>
                                </div>
                                <p class="text-xs text-gray-500 font-bold uppercase">المكان</p>
                                <p class="font-black text-navy text-lg truncate">{{ Str::limit($activity->location, 15) }}</p>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($activity->location) }}" target="_blank" class="text-xs text-blue-600 hover:underline mt-1 block font-bold">
                                    <i class="fas fa-map ml-1"></i> عرض الخريطة
                                </a>
                            </div>
                        @endif
                        @if($activity->max_participants)
                            <div class="text-center p-3 bg-gray-50 rounded-xl">
                                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-users text-orange-600 text-xl"></i>
                                </div>
                                <p class="text-xs text-gray-500 font-bold uppercase">المشاركون</p>
                                <p class="font-black text-navy text-lg">{{ $activity->users->count() }} / {{ $activity->max_participants }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Map -->
                @if($activity->location)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                        <div class="p-4 flex justify-between items-center border-b border-gray-100 bg-gray-50">
                            <h3 class="font-black text-navy flex items-center gap-2">
                                <i class="fas fa-map text-gold"></i> موقع النشاط
                            </h3>
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($activity->location) }}" target="_blank" class="text-blue-600 text-sm font-bold hover:underline flex items-center gap-1">
                                فتح في Google Maps <i class="fas fa-external-link-alt text-xs"></i>
                            </a>
                        </div>
                        <iframe width="100%" height="350" style="border:0" loading="lazy" allowfullscreen src="https://maps.google.com/maps?q={{ urlencode($activity->location) }}&output=embed"></iframe>
                    </div>
                @endif

                <!-- Description -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-2xl font-black text-navy mb-6 flex items-center gap-2">
                        <i class="fas fa-align-right text-gold"></i> عن النشاط
                    </h3>
                    <p class="text-gray-600 leading-relaxed text-lg whitespace-pre-line">{{ $activity->description }}</p>

                    @if($activity->points || $activity->certificate || $activity->online_link)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                            @if($activity->points)
                                <div class="bg-yellow-50 border border-yellow-200 p-5 rounded-xl flex items-center gap-4">
                                    <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-star text-yellow-600 text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 font-bold">النقاط المكتسبة</p>
                                        <p class="text-2xl font-black text-navy">{{ $activity->points }} نقطة</p>
                                    </div>
                                </div>
                            @endif
                            @if($activity->certificate)
                                <div class="bg-blue-50 border border-blue-200 p-5 rounded-xl flex items-center gap-4">
                                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-certificate text-blue-600 text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 font-bold">الشهادة</p>
                                        <p class="text-xl font-black text-navy">شهادة حضور معتمدة</p>
                                    </div>
                                </div>
                            @endif
                            @if($activity->online_link)
                                <div class="md:col-span-2 bg-emerald-50 border border-emerald-200 p-5 rounded-xl flex items-center gap-4">
                                    <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-video text-emerald-600 text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-500 font-bold">رابط الحضور أونلاين</p>
                                        <a href="{{ $activity->online_link }}" target="_blank" class="text-emerald-700 font-black hover:underline text-lg">انضم عبر الإنترنت الآن <i class="fas fa-external-link-alt text-sm mr-1"></i></a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Ratings Section -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-2xl font-black text-navy mb-6 flex items-center gap-2">
                        <i class="fas fa-star text-gold"></i> تقييمات الطلاب
                        @php $ratingsCount = \App\Models\Rating::where('activity_id', $activity->id)->count(); @endphp
                        @if($ratingsCount > 0)
                            <span class="bg-gray-100 text-gray-600 text-sm px-3 py-1 rounded-full font-bold mr-auto">{{ $ratingsCount }} تقييم</span>
                        @endif
                    </h3>

                    @php
                        $allRatings = \App\Models\Rating::where('activity_id', $activity->id)->get();
                        $avgRating = $allRatings->avg('rating') ?? 0;
                    @endphp

                    @if($ratingsCount > 0)
                        <div class="flex flex-col md:flex-row items-center gap-8 mb-10 p-6 bg-gray-50 rounded-2xl border border-gray-100">
                            <div class="text-center min-w-[150px]">
                                <div class="text-6xl font-black text-navy mb-2">{{ number_format($avgRating, 1) }}</div>
                                <div class="flex text-gold text-xl justify-center mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= floor($avgRating) ? '' : '-o' }}"></i>
                                    @endfor
                                </div>
                                <div class="text-xs text-gray-500 font-bold">متوسط التقييم</div>
                            </div>
                            
                            <div class="flex-1 w-full space-y-3">
                                @for($stars = 5; $stars >= 1; $stars--)
                                    <div class="flex items-center gap-3 text-sm">
                                        <span class="w-6 text-gray-600 font-bold text-left">{{ $stars }}</span>
                                        <i class="fas fa-star text-gold text-xs"></i>
                                        <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden">
                                            @php
                                                $count = $allRatings->where('rating', $stars)->count();
                                                $percent = $ratingsCount > 0 ? ($count / $ratingsCount * 100) : 0;
                                            @endphp
                                            <div class="h-full bg-gold rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                                        </div>
                                        <span class="w-10 text-right text-gray-500 text-xs font-bold">{{ round($percent) }}%</span>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    @endif

                    @auth
                        @php
                            $isRegistered = \Illuminate\Support\Facades\DB::table('registrations')->where('student_id', auth()->id())->where('activity_id', $activity->id)->exists();
                            $userRating = \App\Models\Rating::where('user_id', auth()->id())->where('activity_id', $activity->id)->first();
                        @endphp
                        
                        @if($isRegistered)
                            @if(!$userRating)
                                <div class="mb-10 p-6 bg-blue-50 rounded-2xl border border-blue-100">
                                    <h4 class="font-black text-navy mb-4 flex items-center gap-2"><i class="fas fa-pen text-blue-600"></i> شاركنا رأيك في هذا النشاط</h4>
                                    <form action="{{ route('activities.rate', $activity->id) }}" method="POST" class="space-y-5">
                                        @csrf
                                        <div>
                                            <label class="block text-sm font-bold text-navy mb-3">كم تعطي هذا النشاط من نجوم؟</label>
                                            <div class="star-rating flex gap-2" dir="ltr">
                                                @for($i = 5; $i >= 1; $i--)
                                                    <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" required>
                                                    <label for="star{{ $i }}" class="text-4xl text-gray-300 hover:text-gold transition"><i class="fas fa-star"></i></label>
                                                @endfor
                                            </div>
                                            @error('rating') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-navy mb-2">تعليقك (اختياري)</label>
                                            <textarea name="review" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold resize-none bg-white" placeholder="اكتب رأيك أو ملاحظتك حول النشاط..."></textarea>
                                            @error('review') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                                        </div>
                                        <button type="submit" class="bg-navy text-white px-8 py-3 rounded-xl hover:bg-gold hover:text-navy transition font-bold shadow-lg">
                                            <i class="fas fa-paper-plane ml-2"></i> إرسال التقييم
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="mb-10 p-6 bg-green-50 rounded-2xl border border-green-200 flex items-start gap-4">
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-check text-green-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-green-800 text-lg">شكرًا لتقييمك! 🌟</h4>
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="text-gold">
                                                @for($i=1; $i<=5; $i++) <i class="fas fa-star{{ $i <= $userRating->rating ? '' : '-o' }}"></i> @endfor
                                            </span>
                                            <span class="text-gray-500 text-sm font-bold">- {{ $userRating->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if($userRating->review) <p class="text-gray-700 mt-3 italic">"{{ $userRating->review }}"</p> @endif
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endauth

                    @php $allRatingsWithUsers = \App\Models\Rating::where('activity_id', $activity->id)->with('user')->latest()->get(); @endphp

                    @if($allRatingsWithUsers->count() > 0)
                        <div class="space-y-4">
                            <h4 class="font-black text-navy text-lg mb-4">آخر التقييمات</h4>
                            @foreach($allRatingsWithUsers->take(5) as $rating)
                                <div class="p-5 bg-gray-50 rounded-xl border border-gray-100 hover:border-gold/30 transition">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-navy rounded-full flex items-center justify-center text-white font-bold text-sm">{{ substr($rating->user->name ?? '؟', 0, 1) }}</div>
                                            <div>
                                                <div class="font-bold text-navy">{{ $rating->user->name ?? 'طالب' }}</div>
                                                <div class="text-xs text-gray-400">{{ $rating->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                        <div class="flex text-gold text-sm">
                                            @for($i=1; $i<=5; $i++) <i class="fas fa-star{{ $i <= $rating->rating ? '' : '-o' }}"></i> @endfor
                                        </div>
                                    </div>
                                    @if($rating->review) <p class="text-gray-600 text-sm leading-relaxed pr-1">"{{ $rating->review }}"</p> @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-400 py-8 font-semibold">لا توجد تقييمات بعد. كن أول من يقيّم! ✨</p>
                    @endif
                </div>

                <!-- Survey Section -->
                @auth
                    @if(auth()->user()->role !== 'admin')
                        @php
                            $hasSurveyTable = \Illuminate\Support\Facades\Schema::hasTable('survey_responses');
                            $hasResponded = false;
                            $questionsCount = 0;
                            if ($hasSurveyTable) {
                                try {
                                    $hasResponded = \App\Models\SurveyResponse::where('user_id', auth()->id())->where('activity_id', $activity->id)->exists();
                                    $questionsCount = \App\Models\SurveyQuestion::where('is_active', true)->count();
                                } catch (\Exception $e) {}
                            }
                        @endphp
                        @if($hasSurveyTable && $questionsCount > 0)
                            <div class="bg-white rounded-2xl shadow-lg p-8 border-t-4 border-indigo-500">
                                <h3 class="text-2xl font-black text-navy mb-6 flex items-center gap-2"><i class="fas fa-poll text-indigo-500"></i> استبيان النشاط</h3>
                                @if($hasResponded)
                                    <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl flex items-center gap-3">
                                        <i class="fas fa-check-circle text-xl"></i>
                                        <div><p class="font-bold">شكراً لمشاركتك! 🎉</p><p class="text-sm">لقد قمت بملء الاستبيان بنجاح</p></div>
                                    </div>
                                @else
                                    <div class="bg-indigo-50 border border-indigo-200 p-6 rounded-xl text-center">
                                        <i class="fas fa-clipboard-list text-4xl text-indigo-600 mb-4"></i>
                                        <p class="text-navy mb-5 font-bold text-lg">نرجو منك ملء الاستبيان لمساعدتنا في التحسين</p>
                                        <a href="{{ route('student.survey.show', $activity->id) }}" class="inline-block w-full md:w-auto bg-indigo-600 text-white py-3 px-8 rounded-xl hover:bg-indigo-700 transition font-bold shadow-lg">
                                            <i class="fas fa-poll ml-2"></i> املأ الاستبيان الآن
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endif
                @endauth

                <!-- Registered Users -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-2xl font-black text-navy mb-6 flex items-center gap-2">
                        <i class="fas fa-users text-gold"></i> المسجلون في النشاط
                        <span class="bg-gray-100 text-gray-600 text-sm px-3 py-1 rounded-full font-bold mr-auto">{{ $activity->users->count() }} / {{ $activity->max_participants ?? '∞' }}</span>
                    </h3>

                    @if($activity->max_participants)
                        @php $percentage = ($activity->users->count() / $activity->max_participants) * 100; @endphp
                        <div class="w-full bg-gray-200 rounded-full h-3 mb-6 overflow-hidden">
                            <div class="bg-gold h-3 rounded-full transition-all duration-500" style="width: {{ min($percentage, 100) }}%"></div>
                        </div>
                    @endif

                    @if($activity->users->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($activity->users->take(6) as $user)
                                <div class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-gold/30 transition">
                                    <div class="w-12 h-12 bg-navy rounded-full flex items-center justify-center text-white font-bold ml-4 shadow-md">{{ substr($user->name, 0, 1) }}</div>
                                    <div>
                                        <p class="font-bold text-navy">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($activity->users->count() > 6)
                            <p class="text-center text-gray-500 mt-6 text-sm font-bold">و {{ $activity->users->count() - 6 }} مشاركين آخرين...</p>
                        @endif
                    @else
                        <div class="text-center py-12 text-gray-400 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                            <i class="fas fa-user-slash text-5xl mb-4 opacity-50"></i>
                            <p class="font-bold text-lg">لا يوجد مسجلين حتى الآن</p>
                            <p class="text-sm mt-2">كن أول من يسجل!</p>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Left Column: Sidebar -->
            <div class="space-y-6">
                
                <!-- Registration Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24 border border-gray-100">
                    <h3 class="text-xl font-black text-navy mb-6 flex items-center gap-2"><i class="fas fa-clipboard-list text-gold"></i> التسجيل في النشاط</h3>

                    @auth
                        @php
                            $isRegistered = \Illuminate\Support\Facades\DB::table('registrations')->where('student_id', auth()->id())->where('activity_id', $activity->id)->exists();
                            $isFull = $activity->max_participants && $activity->users->count() >= $activity->max_participants;
                            $isClosed = $activity->status !== 'مفتوح';
                        @endphp

                        @if(auth()->user()->role === 'admin')
                            <div class="bg-indigo-50 border border-indigo-200 text-indigo-700 px-4 py-3 rounded-xl text-center font-bold mb-4">
                                <i class="fas fa-user-shield ml-2"></i> أنت مدير النظام
                            </div>
                            <a href="{{ route('admin.registrations', $activity->id) }}" class="block w-full bg-emerald-600 text-white text-center py-3 rounded-xl hover:bg-emerald-700 transition font-bold shadow-md">
                                <i class="fas fa-users ml-2"></i> عرض المسجلين
                            </a>
                        @elseif($isRegistered)
                            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-5 rounded-xl text-center mb-4">
                                <i class="fas fa-check-circle text-green-500 text-3xl mb-2 block"></i>
                                <p class="font-black text-lg">أنت مسجل في هذا النشاط</p>
                                <p class="text-sm text-green-600 mt-1 font-semibold">نراك قريباً!</p>
                            </div>
                            <form action="{{ route('activities.unregister', $activity->id) }}" method="POST" class="mt-3" onsubmit="return confirm('هل تريد إلغاء تسجيلك؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full bg-red-50 text-red-600 border border-red-200 py-3 rounded-xl hover:bg-red-100 transition font-bold text-sm flex items-center justify-center gap-2">
                                    <i class="fas fa-times"></i> إلغاء التسجيل
                                </button>
                            </form>
                        @elseif($isFull)
                            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-5 rounded-xl text-center">
                                <i class="fas fa-times-circle text-red-500 text-3xl mb-2 block"></i>
                                <p class="font-black">اكتمل العدد</p>
                                <p class="text-sm mt-1">لا توجد أماكن متاحة</p>
                            </div>
                        @elseif($isClosed)
                            <div class="bg-gray-50 border border-gray-200 text-gray-600 px-4 py-5 rounded-xl text-center">
                                <i class="fas fa-ban text-gray-400 text-3xl mb-2 block"></i>
                                <p class="font-black">التسجيل مغلق</p>
                            </div>
                        @else
                            <form action="{{ route('activities.register', $activity->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-gold text-navy py-4 rounded-xl hover:bg-yellow-500 transition font-black text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center gap-2">
                                    <i class="fas fa-check"></i> سجل الآن
                                </button>
                            </form>
                        @endif
                    @else
                        <div class="text-center p-6 bg-gray-50 rounded-xl border border-dashed border-gray-300 mb-4">
                            <i class="fas fa-lock text-3xl text-gray-400 mb-3"></i>
                            <p class="text-gray-600 font-bold mb-4">يجب تسجيل الدخول للمشاركة</p>
                            <a href="/login" class="block w-full bg-navy text-white py-3 rounded-xl hover:bg-gold hover:text-navy transition font-bold mb-3">تسجيل الدخول</a>
                            <a href="{{ route('register') }}" class="block w-full bg-white border-2 border-navy text-navy py-3 rounded-xl hover:bg-gray-50 transition font-bold">إنشاء حساب جديد</a>
                        </div>
                    @endauth

                    @auth
                        <form action="{{ route('activities.favorite', $activity->id) }}" method="POST" class="mt-4">
                            @csrf
                            @php $isFav = \App\Models\Favorite::where('user_id', auth()->id())->where('activity_id', $activity->id)->exists(); @endphp
                            <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 rounded-xl border font-bold transition {{ $isFav ? 'bg-red-50 text-red-500 border-red-200' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                                <i class="fas fa-heart {{ $isFav ? 'animate-pulse' : '' }}"></i>
                                {{ $isFav ? 'إزالة من المفضلة' : 'إضافة للمفضلة' }}
                            </button>
                        </form>
                    @endauth
                </div>

                <!-- Organizer Card -->
                @if($activity->creator)
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-lg font-black text-navy mb-5 flex items-center gap-2"><i class="fas fa-user-tie text-gold"></i> المنظم / المشرف</h3>
                    
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-16 h-16 bg-gradient-to-br from-navy to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-2xl shadow-lg flex-shrink-0">
                            {{ substr($activity->creator->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-black text-navy text-lg truncate">{{ $activity->creator->name }}</p>
                            <p class="text-sm text-gray-500 flex items-center gap-1 mt-1 truncate"><i class="fas fa-envelope text-xs"></i> {{ $activity->creator->email }}</p>
                            @if($activity->creator->role)
                                <span class="inline-block mt-2 text-xs bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full font-bold">
                                    @if($activity->creator->role === 'admin') <i class="fas fa-user-shield ml-1"></i>مدير النظام
                                    @elseif($activity->creator->role === 'staff') <i class="fas fa-user-cog ml-1"></i>مشرف
                                    @else <i class="fas fa-user ml-1"></i>{{ $activity->creator->role }} @endif
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="pt-5 border-t border-gray-100 grid grid-cols-2 gap-3 text-center">
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs text-gray-500 mb-1 font-bold">الأنشطة المنظمة</p>
                            <p class="text-xl font-black text-navy">{{ \App\Models\Activity::where('created_by', $activity->creator->id)->count() }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs text-gray-500 mb-1 font-bold">تاريخ الانضمام</p>
                            <p class="text-sm font-black text-navy">{{ $activity->creator->created_at->format('Y/m') }}</p>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

</body>
</html>