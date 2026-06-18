<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Student Activities') }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: #f3f4f6;
        }
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        [x-cloak] { display: none !important; }
        
        /* تخصيص شريط التمرير للإشعارات */
        .notif-scroll::-webkit-scrollbar { width: 6px; }
        .notif-scroll::-webkit-scrollbar-track { background: transparent; }
        .notif-scroll::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- ✅ Navigation Bar في الأعلى -->
    <nav class="sidebar shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-graduation-cap"></i>
                    نظام الأنشطة الطلابية
                </a>
                
                <div class="flex items-center gap-4">
                    
                    @auth
                        <!-- 🔔 جرس الإشعارات -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="relative text-white hover:bg-white/20 p-2 rounded-lg transition focus:outline-none">
                                <i class="fas fa-bell text-xl"></i>
                                
                                @php
                                    $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                                @endphp
                                
                                @if($unreadCount > 0)
                                    <span class="absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full animate-pulse border-2 border-[#764ba2]">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </button>

                            <!-- القائمة المنسدلة للإشعارات -->
                            <div x-show="open" 
                                 @click.outside="open = false" 
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute left-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden origin-top-left">
                                
                                <div class="p-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                                    <h3 class="font-bold text-gray-800">الإشعارات</h3>
                                    <a href="{{ route('notifications.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">عرض الكل</a>
                                </div>
                                
                                <div class="max-h-80 overflow-y-auto notif-scroll">
                                    @php
                                        $recentNotifications = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(6)->get();
                                    @endphp
                                    
                                    @forelse($recentNotifications as $notif)
                                        <a href="{{ route('notifications.read', $notif->id) }}" 
                                           class="block p-4 border-b border-gray-50 hover:bg-indigo-50/50 transition group {{ !$notif->is_read ? 'bg-blue-50/30' : '' }}">
                                            <div class="flex items-start gap-3">
                                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-indigo-200 transition">
                                                    <i class="fas fa-{{ $notif->icon ?? 'bell' }} text-indigo-600"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-bold text-gray-800 text-sm truncate">{{ $notif->title }}</p>
                                                    <p class="text-gray-500 text-xs mt-1 line-clamp-2 leading-relaxed">{{ $notif->message }}</p>
                                                    <p class="text-gray-400 text-[10px] mt-2">{{ $notif->created_at->diffForHumans() }}</p>
                                                </div>
                                                @if(!$notif->is_read)
                                                    <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full mt-2 flex-shrink-0"></span>
                                                @endif
                                            </div>
                                        </a>
                                    @empty
                                        <div class="p-8 text-center text-gray-400">
                                            <i class="fas fa-bell-slash text-3xl mb-3 opacity-50"></i>
                                            <p class="text-sm font-medium">لا توجد إشعارات جديدة</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <!-- نهاية الجرس -->

                        <span class="text-white font-semibold hidden md:inline">{{ Auth::user()->name }}</span>
                        
                        <!-- Dropdown للمستخدم -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white font-bold hover:bg-white/30 transition">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user ml-2"></i> الملف الشخصي
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-right px-4 py-2 text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt ml-2"></i> تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:bg-white/20 px-4 py-2 rounded-lg transition font-bold">دخول</a>
                    @endauth
                    
                </div>
            </div>
        </div>
    </nav>

    <!-- ✅ المحتوى الرئيسي مباشرة تحت الـ Navbar -->
    <main class="flex-grow container mx-auto px-4 py-6">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <!-- Alpine.js for dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>
</html>