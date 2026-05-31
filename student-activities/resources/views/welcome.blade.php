<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة الأنشطة الطلابية</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .hero-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #312e81 100%);
            position: relative;
            overflow: hidden;
        }
        .hero-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1523580494863-6f3031224c94?w=1400&q=80') center/cover no-repeat;
            opacity: 0.15;
        }
        .glass {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.15);
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .gold-btn {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }
        .gold-btn:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
        }
        .nav-dark {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
        }
        .leaderboard-item {
            transition: all 0.2s ease;
        }
        .leaderboard-item:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(-3px);
        }
        .activity-stream {
            animation: slideUp 0.5s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .stat-number {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-gray-950 text-white">

    <!-- Navbar -->
    <nav class="nav-dark sticky top-0 z-50 border-b border-white/10">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-extrabold text-white">نظام إدارة الأنشطة الطلابية</span>
                </a>
                <div class="flex items-center gap-6">
                    <a href="{{ route('activities.index') }}" class="text-gray-300 hover:text-white font-semibold transition">الأنشطة</a>
                    @auth
                        <a href="/dashboard" class="text-gray-300 hover:text-white font-semibold transition">لوحة التحكم</a>
                        <a href="/dashboard" class="gold-btn text-white px-6 py-2.5 rounded-xl font-bold transition shadow-lg">
                            <i class="fas fa-th-large ml-2"></i>الداشبورد
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white font-semibold transition">تسجيل الدخول</a>
                        <a href="{{ route('register') }}" class="gold-btn text-white px-6 py-2.5 rounded-xl font-bold transition shadow-lg">
                            <i class="fas fa-user-plus ml-2"></i>سجل مجاناً
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg min-h-screen flex items-center py-20">
        <div class="container mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">

                <!-- Left: Leaderboard -->
                <div class="glass rounded-2xl p-6 order-3 lg:order-1">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-white text-lg">
                            <i class="fas fa-trophy text-yellow-400 ml-2"></i>
                            Leaderboard
                        </h3>
                        <span class="text-xs text-gray-400 bg-white/10 px-3 py-1 rounded-full">هذا الشهر</span>
                    </div>
                    @php
                        $topStudents = \App\Models\User::where('role', 'student')
                            ->orderBy('points', 'desc')
                            ->take(5)
                            ->get();
                    @endphp
                    <div class="space-y-3">
                        @forelse($topStudents as $index => $student)
                            <div class="leaderboard-item flex items-center gap-3 p-2 rounded-xl">
                                <div class="w-7 h-7 flex items-center justify-center rounded-full text-xs font-extrabold
                                    {{ $index === 0 ? 'bg-yellow-400 text-gray-900' : ($index === 1 ? 'bg-gray-300 text-gray-900' : ($index === 2 ? 'bg-amber-600 text-white' : 'bg-white/10 text-white')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="w-9 h-9 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr($student->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-white text-sm">{{ $student->name }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ \App\Models\User::where('role','student')->orderBy('points','desc')->take(5)->get()->where('id', $student->id)->first() ? 'طالب نشط' : '' }}
                                    </p>
                                </div>
                                <span class="stat-number font-extrabold text-lg">{{ $student->points ?? 0 }}</span>
                            </div>
                        @empty
                            @for($i = 1; $i <= 4; $i++)
                                <div class="leaderboard-item flex items-center gap-3 p-2 rounded-xl">
                                    <div class="w-7 h-7 flex items-center justify-center rounded-full text-xs font-extrabold bg-white/10 text-white">{{ $i }}</div>
                                    <div class="w-9 h-9 bg-indigo-500/50 rounded-full"></div>
                                    <div class="flex-1">
                                        <div class="h-3 bg-white/10 rounded w-24"></div>
                                    </div>
                                    <span class="text-gray-500 font-bold">0</span>
                                </div>
                            @endfor
                        @endforelse
                    </div>
                </div>

                <!-- Center: Hero Content -->
                <div class="text-center order-1 lg:order-2">
                    <div class="w-20 h-20 bg-indigo-600/30 border-2 border-indigo-400 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-graduation-cap text-4xl text-indigo-300"></i>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-black mb-6 leading-tight">
                        اكتشف وشارك في
                        <span class="text-yellow-400">الأنشطة الطلابية</span>
                    </h1>
                    <p class="text-gray-300 text-lg mb-10 leading-relaxed max-w-lg mx-auto">
                        منصة متكاملة تتيح للطلاب التسجيل في الأنشطة المختلفة، اكتساب النقاط، والحصول على شهادات المشاركة
                    </p>
                    <div class="flex flex-wrap justify-center gap-4">
                        @auth
                            <a href="{{ route('activities.index') }}" class="gold-btn text-white px-10 py-4 rounded-2xl font-extrabold text-lg shadow-xl transition">
                                <i class="fas fa-search ml-2"></i> تصفح الأنشطة
                            </a>
                            <a href="/dashboard" class="glass text-white px-10 py-4 rounded-2xl font-extrabold text-lg transition hover:bg-white/20">
                                <i class="fas fa-th-large ml-2"></i> لوحة التحكم
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="gold-btn text-white px-10 py-4 rounded-2xl font-extrabold text-lg shadow-xl transition">
                                <i class="fas fa-user-plus ml-2"></i> سجل مجاناً
                            </a>
                            <a href="{{ route('activities.index') }}" class="glass text-white px-10 py-4 rounded-2xl font-extrabold text-lg transition hover:bg-white/20">
                                <i class="fas fa-eye ml-2"></i> تصفح الأنشطة
                            </a>
                        @endauth
                    </div>

                    <!-- Stats -->
                    <div class="flex justify-center gap-10 mt-12">
                        <div class="text-center">
                            <p class="stat-number text-4xl font-black">{{ \App\Models\Activity::count() }}+</p>
                            <p class="text-gray-400 text-sm mt-1">نشاط متاح</p>
                        </div>
                        <div class="text-center">
                            <p class="stat-number text-4xl font-black">{{ \App\Models\User::count() }}+</p>
                            <p class="text-gray-400 text-sm mt-1">طالب مسجل</p>
                        </div>
                        <div class="text-center">
                            <p class="stat-number text-4xl font-black">{{ \Illuminate\Support\Facades\DB::table('registrations')->count() }}+</p>
                            <p class="text-gray-400 text-sm mt-1">تسجيل</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Feature Cards -->
                <div class="space-y-4 order-2 lg:order-3">
                    <div class="glass rounded-2xl p-5 card-hover">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-indigo-500/30 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-plus text-indigo-300 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-white">Registration</h4>
                                <p class="text-gray-400 text-sm">منصة متكاملة لتتيح للطلاب التسجيل في الأنشطة، شهادات المشاركة.</p>
                            </div>
                        </div>
                        @guest
                            <a href="{{ route('register') }}" class="mt-3 gold-btn text-white px-4 py-2 rounded-lg text-sm font-bold inline-block">
                                <i class="fas fa-user-plus ml-1"></i> ابدأ مجاناً
                            </a>
                        @endguest
                    </div>

                    <div class="glass rounded-2xl p-5 card-hover">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-yellow-500/30 rounded-xl flex items-center justify-center">
                                <i class="fas fa-star text-yellow-300 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-white">Points</h4>
                                <p class="text-gray-400 text-sm">منصة متكاملة لتتيح للطلاب اكتساب النقاط والتنافس المشارك.</p>
                            </div>
                        </div>
                        @guest
                            <a href="{{ route('register') }}" class="mt-3 gold-btn text-white px-4 py-2 rounded-lg text-sm font-bold inline-block">
                                <i class="fas fa-user-plus ml-1"></i> ابدأ مجاناً
                            </a>
                        @endguest
                    </div>

                    <div class="glass rounded-2xl p-5 card-hover">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-green-500/30 rounded-xl flex items-center justify-center">
                                <i class="fas fa-certificate text-green-300 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-white">Certificates</h4>
                                <p class="text-gray-400 text-sm">منصة متكاملة لتتيح للطلاب الحصول في الشهادات الطلابية المعتمدة.</p>
                            </div>
                        </div>
                        @guest
                            <a href="{{ route('register') }}" class="mt-3 gold-btn text-white px-4 py-2 rounded-lg text-sm font-bold inline-block">
                                <i class="fas fa-user-plus ml-1"></i> ابدأ مجاناً
                            </a>
                        @endguest
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Latest Activities Section -->
    <section class="py-20 px-6 bg-gray-900">
        <div class="container mx-auto">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="text-4xl font-extrabold text-white mb-2">أحدث الأنشطة</h2>
                    <p class="text-gray-400 text-lg">اكتشف الأنشطة المتاحة للتسجيل</p>
                </div>
                <a href="{{ route('activities.index') }}" class="gold-btn text-white px-6 py-3 rounded-xl font-bold transition shadow-lg">
                    عرض الكل <i class="fas fa-arrow-left mr-2"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach(\App\Models\Activity::with('activityType')->where('status', 'مفتوح')->latest()->take(3)->get() as $activity)
                    <div class="card-hover bg-gray-800 rounded-2xl overflow-hidden border border-gray-700">
                        <div class="h-44 bg-gradient-to-br from-indigo-600 to-purple-700 relative flex items-center justify-center">
                            @if($activity->image)
                                <img src="{{ asset('storage/' . $activity->image) }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-calendar-alt text-5xl text-white/20"></i>
                            @endif
                            <span class="absolute top-3 right-3 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                مفتوح
                            </span>
                            @if($activity->points)
                                <span class="absolute top-3 left-3 bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                    <i class="fas fa-star ml-1"></i>{{ $activity->points }}
                                </span>
                            @endif
                        </div>
                        <div class="p-5">
                            <span class="bg-indigo-900 text-indigo-300 text-xs font-bold px-3 py-1 rounded-full">
                                {{ $activity->activityType->name ?? 'عام' }}
                            </span>
                            <h3 class="font-bold text-white text-lg mt-3 mb-2 line-clamp-1">{{ $activity->title }}</h3>
                            <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ Str::limit($activity->description, 80) }}</p>
                            <div class="flex justify-between items-center">
                                @if($activity->date)
                                    <span class="text-gray-500 text-sm">
                                        <i class="fas fa-calendar text-indigo-400 ml-1"></i>
                                        {{ \Carbon\Carbon::parse($activity->date)->format('Y/m/d') }}
                                    </span>
                                @endif
                                <a href="{{ route('activities.show', $activity->id) }}" class="gold-btn text-white px-4 py-2 rounded-lg text-sm font-bold transition">
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
    <section class="py-20 px-6 bg-gray-950 text-center">
        <div class="container mx-auto">
            <div class="glass rounded-3xl p-16 max-w-3xl mx-auto">
                <i class="fas fa-rocket text-5xl text-yellow-400 mb-6 block"></i>
                <h2 class="text-4xl font-extrabold text-white mb-4">هل أنت مستعد للانضمام؟</h2>
                <p class="text-gray-400 text-xl mb-10">سجّل الآن مجاناً وابدأ رحلتك في عالم الأنشطة الطلابية</p>
                <a href="{{ route('register') }}" class="gold-btn text-white px-12 py-4 rounded-2xl font-extrabold text-xl shadow-xl inline-block transition">
                    <i class="fas fa-user-plus ml-2"></i> سجّل الآن مجاناً
                </a>
            </div>
        </div>
    </section>
    @endguest

    <!-- Footer -->
    <footer class="bg-gray-900 border-t border-gray-800 py-10 px-6 text-center">
        <div class="container mx-auto">
            <div class="flex items-center justify-center gap-3 mb-4">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-white text-sm"></i>
                </div>
                <span class="text-xl font-bold text-white">نظام إدارة الأنشطة الطلابية</span>
            </div>
            <p class="text-gray-500">جميع الحقوق محفوظة © {{ date('Y') }}</p>
        </div>
    </footer>

</body>
</html>