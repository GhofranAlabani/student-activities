<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Language" content="en">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة الأنشطة الطلابية</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .bg-navy { background-color: #0a1929; }
        .bg-navy-light { background-color: #112240; }
        .text-gold { color: #d4a017; }
        .bg-gold { background-color: #d4a017; }
        .bg-gold:hover { background-color: #b8860b; }
        .border-gold { border-color: #d4a017; }
        .gradient-navy { background: linear-gradient(135deg, #0a1929 0%, #1e3a5f 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(212, 160, 23, 0.2); }
    </style>
</head>
<body class="bg-[#f5f0e8]">

    <!-- Navbar -->
    <nav class="bg-navy text-white shadow-2xl sticky top-0 z-50 border-b-2 border-gold">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <!-- ✅ تغيير الشعار من "أن" إلى "من" -->
                    <div class="w-12 h-12 bg-gold rounded-xl flex items-center justify-center text-navy font-black text-2xl shadow-lg">
                        من
                    </div>
                    <div>
                        <!-- ✅ تغيير الاسم في النافبار -->
                        <h1 class="text-2xl font-bold text-gold">منصة الأنشطة الطلابية</h1>
                        
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('student.dashboard') }}" 
                           class="bg-gold text-navy px-6 py-2.5 rounded-xl hover:bg-yellow-600 transition font-bold shadow-lg">
                            <i class="fas fa-arrow-right ml-2"></i> لوحة التحكم
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-gold font-semibold transition px-4 py-2">
                            دخول النظام
                        </a>
                        <a href="{{ route('register') }}" class="bg-gold text-navy px-6 py-2.5 rounded-xl hover:bg-yellow-600 transition font-bold shadow-lg">
                            <i class="fas fa-user-plus ml-2"></i> سجل الآن
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-navy text-white py-20 px-6 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 right-20 w-96 h-96 bg-yellow-400 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 left-20 w-96 h-96 bg-yellow-600 rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <!-- Right Side - Content -->
                <div class="space-y-8">
                    <!-- ✅ تغيير النص هنا -->
                    <div class="inline-block bg-gold/20 border-2 border-gold text-gold px-6 py-2 rounded-full text-sm font-bold">
                        <i class="fas fa-star ml-2"></i>
                        منصة متكاملة للأنشطة الطلابية
                    </div>
                    
                    <h1 class="text-5xl md:text-6xl font-black leading-tight">
                        أنشطتك <span class="text-gold">منظّمة</span><br>
                        ومتابعة في <span class="text-gold">مكان واحد</span>
                    </h1>
                    
                    <!-- ✅ تغيير الوصف هنا -->
                    <p class="text-xl text-gray-300 leading-relaxed max-w-2xl">
                       منصة متكاملة لإدارة الأنشطة الطلابية، تنسيق الفعاليات ومتابعة مشاركة الطلاب 
                    </p>

                    <div class="flex gap-4 pt-4">
                        @auth
                            <a href="{{ route('activities.index') }}" 
                               class="bg-gold text-navy px-8 py-4 rounded-xl hover:bg-yellow-600 transition font-bold text-lg shadow-xl">
                                <i class="fas fa-search ml-2"></i>
                                تصفح الأنشطة
                            </a>
                        @else
                            <a href="{{ route('register') }}" 
                               class="bg-gold text-navy px-8 py-4 rounded-xl hover:bg-yellow-600 transition font-bold text-lg shadow-xl">
                                <i class="fas fa-user-plus ml-2"></i>
                                ابدأ الآن
                            </a>
                            <a href="{{ route('activities.index') }}" 
                               class="border-2 border-white text-white px-8 py-4 rounded-xl hover:bg-white hover:text-navy transition font-bold text-lg">
                                تجوّل في النظام
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Left Side - Stats -->
                <div class="grid grid-cols-2 gap-4">
                    @php
                        $totalStudents = \App\Models\User::where('role', 'student')->count();
                        $totalActivities = \App\Models\Activity::count();
                        $totalRegistrations = \Illuminate\Support\Facades\DB::table('registrations')->count();
                    @endphp
                    
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-center card-hover">
                        <div class="text-5xl font-black text-gold mb-2">{{ $totalActivities }}</div>
                        <div class="text-gray-300 font-semibold">نشاط متاح</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-center card-hover">
                        <div class="text-5xl font-black text-gold mb-2">{{ $totalStudents }}</div>
                        <div class="text-gray-300 font-semibold">طالب مشارك</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-center card-hover">
                        <div class="text-5xl font-black text-gold mb-2">{{ $totalRegistrations }}</div>
                        <div class="text-gray-300 font-semibold">مشاركة فاعلة</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-center card-hover">
                        <div class="text-5xl font-black text-gold mb-2">4%</div>
                        <div class="text-gray-300 font-semibold">معدل المشاركة</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-[#f5f0e8]">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <div class="text-gold font-bold text-sm mb-2">مميزات المنصة</div>
                <h2 class="text-4xl font-black text-navy mb-4">كل ما تحتاجه في منصة واحدة</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">من إدارة الأندية إلى تقارير المشاركة — غطينا كل جانب من جوانب إدارة الأنشطة الطلابية</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition border-b-4 border-gold text-center card-hover">
                    <div class="w-16 h-16 bg-gold/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-calendar-check text-3xl text-gold"></i>
                    </div>
                    <h3 class="text-xl font-bold text-navy mb-3">تسجيل سهل</h3>
                    <p class="text-gray-600 leading-relaxed">سجّل في الأنشطة بنقرة واحدة وتابع مواعيدها بسهولة</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition border-b-4 border-gold text-center card-hover">
                    <div class="w-16 h-16 bg-gold/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-star text-3xl text-gold"></i>
                    </div>
                    <h3 class="text-xl font-bold text-navy mb-3">اكسب النقاط</h3>
                    <p class="text-gray-600 leading-relaxed">احصل على نقاط لكل نشاط تشارك فيه وتنافس مع زملائك</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition border-b-4 border-gold text-center card-hover">
                    <div class="w-16 h-16 bg-gold/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-certificate text-3xl text-gold"></i>
                    </div>
                    <h3 class="text-xl font-bold text-navy mb-3">شهادات معتمدة</h3>
                    <p class="text-gray-600 leading-relaxed">احصل على شهادات حضور رسمية لكل نشاط تشارك فيه</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition border-b-4 border-gold text-center card-hover">
                    <div class="w-16 h-16 bg-gold/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-chart-line text-3xl text-gold"></i>
                    </div>
                    <h3 class="text-xl font-bold text-navy mb-3">متابعة مستمرة</h3>
                    <p class="text-gray-600 leading-relaxed">تابع تقدمك وأنشطتك في لوحة تحكم متكاملة</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Activities Preview -->
    @php
        $latestActivities = \App\Models\Activity::where('status', 'مفتوح')->latest()->take(3)->get();
    @endphp
    @if($latestActivities->count() > 0)
        <section class="py-20 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center mb-12">
                    <div class="text-gold font-bold text-sm mb-2">أحدث الفعاليات</div>
                    <h2 class="text-4xl font-black text-navy mb-4">أنشطة متاحة للتسجيل</h2>
                    <p class="text-gray-600">اكتشف الأنشطة الجديدة وسجّل فيها الآن</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($latestActivities as $activity)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 card-hover">
                            <div class="h-48 bg-gradient-to-br from-navy to-navy-light flex items-center justify-center relative">
                                @if($activity->image)
                                    <img src="{{ asset('storage/' . $activity->image) }}" 
                                         alt="{{ $activity->title }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-calendar-alt text-6xl text-white/30"></i>
                                @endif
                                <span class="absolute top-3 right-3 bg-gold text-navy text-xs font-bold px-3 py-1 rounded-full">
                                    متاح للتسجيل
                                </span>
                            </div>
                            <div class="p-6">
                                <span class="bg-navy/10 text-navy text-xs font-bold px-3 py-1 rounded-full">
                                    {{ $activity->activityType->name ?? 'عام' }}
                                </span>
                                <h3 class="font-bold text-navy text-lg mt-3 mb-2">{{ $activity->title }}</h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($activity->description, 80) }}</p>
                                <div class="flex justify-between items-center">
                                    @if($activity->date)
                                        <span class="text-gray-600 text-sm">
                                            <i class="fas fa-calendar text-gold ml-1"></i>
                                            {{ \Carbon\Carbon::parse($activity->date)->format('Y/m/d') }}
                                        </span>
                                    @endif
                                    <a href="{{ route('activities.show', $activity->id) }}" 
                                       class="bg-gold text-navy px-4 py-2 rounded-lg text-sm font-bold hover:bg-yellow-600 transition">
                                        التفاصيل
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-12">
                    <a href="{{ route('activities.index') }}" 
                       class="inline-block bg-navy text-white px-10 py-4 rounded-xl hover:bg-navy-light transition font-bold text-lg shadow-xl">
                        عرض كل الأنشطة
                        <i class="fas fa-arrow-left mr-2"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- CTA Section -->
    @guest
    <section class="gradient-navy py-20 px-6 text-white text-center">
        <div class="container mx-auto">
            <h2 class="text-4xl font-black mb-4">هل أنت مستعد للانضمام؟</h2>
            <p class="text-gray-300 text-xl mb-10 max-w-xl mx-auto">سجّل الآن مجاناً وابدأ رحلتك في عالم الأنشطة الطلابية</p>
            <a href="{{ route('register') }}" 
               class="inline-block bg-gold text-navy px-12 py-4 rounded-2xl font-extrabold text-xl hover:bg-yellow-600 transition shadow-xl">
                <i class="fas fa-rocket ml-2"></i>
                سجّل الآن
            </a>
        </div>
    </section>
    @endguest

    <!-- Footer -->
    <footer class="bg-navy text-white py-8 border-t-2 border-gold">
        <div class="container mx-auto px-6 text-center">
            <div class="flex items-center justify-center gap-3 mb-4">
                <!-- ✅ تغيير الشعار في الفوتر -->
                <div class="w-10 h-10 bg-gold rounded-xl flex items-center justify-center text-navy font-bold">
                    من
                </div>
                <!-- ✅ تغيير الاسم في الفوتر -->
                <span class="text-xl font-bold text-gold">منصة الأنشطة الطلابية</span>
            </div>
            <p class="text-gray-400 text-sm">جميع الحقوق محفوظة © {{ date('Y') }} — الفصل الثاني ١٤٤٧هـ</p>
        </div>
    </footer>

</body>
</html>