<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام الأنشطة الطلابية</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .hero-bg {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .floating {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="text-2xl font-extrabold text-indigo-600 flex items-center gap-2">
                    <i class="fas fa-graduation-cap text-3xl"></i>
                    نظام الأنشطة الطلابية
                </a>
                <div class="flex items-center gap-4">
                    <a href="{{ route('activities.index') }}" class="text-gray-600 hover:text-indigo-600 font-semibold transition">
                        الأنشطة
                    </a>
                    @auth
                        <a href="/dashboard" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl hover:bg-indigo-700 transition font-bold shadow-md">
                            لوحة التحكم
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 font-semibold transition">
                            تسجيل الدخول
                        </a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl hover:bg-indigo-700 transition font-bold shadow-md">
                            سجل مجاناً
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg py-24 px-6 text-white relative overflow-hidden">
        <!-- Background decorations -->
        <div class="absolute top-10 right-10 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-10 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>

        <div class="container mx-auto text-center relative z-10">
            <div class="floating inline-block mb-6">
                <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mx-auto shadow-xl">
                    <i class="fas fa-graduation-cap text-5xl text-white"></i>
                </div>
            </div>

            <h1 class="text-5xl md:text-6xl font-extrabold mb-6 leading-tight">
                اكتشف وشارك في
                <span class="text-yellow-300">الأنشطة الطلابية</span>
            </h1>

            <p class="text-xl text-indigo-100 mb-10 max-w-2xl mx-auto leading-relaxed">
                منصة متكاملة تتيح للطلاب التسجيل في الأنشطة المختلفة، اكتساب النقاط، والحصول على شهادات المشاركة
            </p>

            <div class="flex flex-wrap justify-center gap-4">
                @auth
                    <a href="{{ route('activities.index') }}" class="bg-white text-indigo-600 px-10 py-4 rounded-2xl font-extrabold text-lg hover:bg-indigo-50 transition shadow-xl">
                        <i class="fas fa-search ml-2"></i>
                        تصفح الأنشطة
                    </a>
                    <a href="/dashboard" class="bg-white/20 text-white px-10 py-4 rounded-2xl font-extrabold text-lg hover:bg-white/30 transition border-2 border-white/30">
                        <i class="fas fa-th-large ml-2"></i>
                        لوحة التحكم
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-10 py-4 rounded-2xl font-extrabold text-lg hover:bg-indigo-50 transition shadow-xl">
                        <i class="fas fa-user-plus ml-2"></i>
                        ابدأ الآن مجاناً
                    </a>
                    <a href="{{ route('activities.index') }}" class="bg-white/20 text-white px-10 py-4 rounded-2xl font-extrabold text-lg hover:bg-white/30 transition border-2 border-white/30">
                        <i class="fas fa-eye ml-2"></i>
                        تصفح الأنشطة
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-white py-16 shadow-sm">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div>
                    <p class="text-5xl font-extrabold text-indigo-600 mb-2">{{ \App\Models\Activity::count() }}+</p>
                    <p class="text-gray-600 font-semibold text-lg">نشاط متاح</p>
                </div>
                <div>
                    <p class="text-5xl font-extrabold text-purple-600 mb-2">{{ \App\Models\User::count() }}+</p>
                    <p class="text-gray-600 font-semibold text-lg">طالب مسجل</p>
                </div>
                <div>
                    <p class="text-5xl font-extrabold text-pink-600 mb-2">{{ \App\Models\ActivityType::count() }}+</p>
                    <p class="text-gray-600 font-semibold text-lg">نوع نشاط</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 px-6">
        <div class="container mx-auto">
            <h2 class="text-4xl font-extrabold text-center text-gray-800 mb-4">لماذا تنضم إلينا؟</h2>
            <p class="text-center text-gray-500 mb-14 text-lg">كل ما تحتاجه لتطوير مهاراتك وتوسيع شبكة علاقاتك</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="card-hover bg-white p-8 rounded-2xl shadow-md border border-gray-100 text-center">
                    <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-calendar-check text-3xl text-indigo-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">تسجيل سهل</h3>
                    <p class="text-gray-500 leading-relaxed">سجّل في الأنشطة التي تهمك بنقرة واحدة وتابع مواعيدها بسهولة</p>
                </div>

                <div class="card-hover bg-white p-8 rounded-2xl shadow-md border border-gray-100 text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-star text-3xl text-yellow-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">اكسب النقاط</h3>
                    <p class="text-gray-500 leading-relaxed">احصل على نقاط لكل نشاط تشارك فيه وتنافس مع زملائك</p>
                </div>

                <div class="card-hover bg-white p-8 rounded-2xl shadow-md border border-gray-100 text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-certificate text-3xl text-blue-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">شهادات معتمدة</h3>
                    <p class="text-gray-500 leading-relaxed">احصل على شهادات حضور رسمية لكل نشاط تشارك فيه</p>
                </div>

                <div class="card-hover bg-white p-8 rounded-2xl shadow-md border border-gray-100 text-center">
                    <div class="w-16 h-16 bg-pink-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-heart text-3xl text-pink-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">قائمة المفضلة</h3>
                    <p class="text-gray-500 leading-relaxed">احفظ الأنشطة التي تعجبك في قائمة المفضلة وارجع إليها وقت ما تشاء</p>
                </div>

                <div class="card-hover bg-white p-8 rounded-2xl shadow-md border border-gray-100 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-users text-3xl text-green-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">تواصل مع الزملاء</h3>
                    <p class="text-gray-500 leading-relaxed">تعرف على زملائك المشاركين في نفس الأنشطة ووسّع شبكة علاقاتك</p>
                </div>

                <div class="card-hover bg-white p-8 rounded-2xl shadow-md border border-gray-100 text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-map-marker-alt text-3xl text-purple-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">أنشطة متنوعة</h3>
                    <p class="text-gray-500 leading-relaxed">أنشطة ثقافية، رياضية، علمية، وترفيهية تناسب جميع الاهتمامات</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Activities -->
    <section class="py-20 px-6 bg-gray-100">
        <div class="container mx-auto">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="text-4xl font-extrabold text-gray-800 mb-2">أحدث الأنشطة</h2>
                    <p class="text-gray-500 text-lg">اكتشف الأنشطة المتاحة للتسجيل</p>
                </div>
                <a href="{{ route('activities.index') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-md">
                    عرض الكل <i class="fas fa-arrow-left mr-2"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach(\App\Models\Activity::with('activityType')->where('status', 'active')->latest()->take(3)->get() as $activity)
                    <div class="card-hover bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
                        <div class="h-44 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center relative">
                            @if($activity->image)
                                <img src="{{ asset('storage/' . $activity->image) }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-calendar-alt text-5xl text-white/30"></i>
                            @endif
                            <span class="absolute top-3 right-3 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                متاح للتسجيل
                            </span>
                        </div>
                        <div class="p-5">
                            <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full border border-indigo-100">
                                {{ $activity->activityType->name ?? 'عام' }}
                            </span>
                            <h3 class="font-bold text-gray-800 text-lg mt-3 mb-2 line-clamp-1">{{ $activity->title }}</h3>
                            <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ Str::limit($activity->description, 80) }}</p>
                            <div class="flex justify-between items-center">
                                @if($activity->date)
                                    <span class="text-gray-500 text-sm">
                                        <i class="fas fa-calendar text-indigo-400 ml-1"></i>
                                        {{ \Carbon\Carbon::parse($activity->date)->format('Y/m/d') }}
                                    </span>
                                @endif
                                <a href="{{ route('activities.show', $activity->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-700 transition">
                                    التفاصيل
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    @guest
    <section class="hero-bg py-20 px-6 text-white text-center">
        <div class="container mx-auto">
            <h2 class="text-4xl font-extrabold mb-4">هل أنت مستعد للانضمام؟</h2>
            <p class="text-indigo-100 text-xl mb-10 max-w-xl mx-auto">سجّل الآن مجاناً وابدأ رحلتك في عالم الأنشطة الطلابية</p>
            <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-12 py-4 rounded-2xl font-extrabold text-xl hover:bg-indigo-50 transition shadow-xl inline-block">
                <i class="fas fa-rocket ml-2"></i>
                سجّل الآن
            </a>
        </div>
    </section>
    @endguest

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-10 px-6 text-center">
        <div class="container mx-auto">
            <div class="flex items-center justify-center gap-2 mb-4">
                <i class="fas fa-graduation-cap text-2xl text-indigo-400"></i>
                <span class="text-xl font-bold">نظام الأنشطة الطلابية</span>
            </div>
            <p class="text-gray-400">جميع الحقوق محفوظة © {{ date('Y') }}</p>
        </div>
    </footer>

</body>
</html>