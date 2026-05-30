<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الطالب</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .sidebar-link:hover { background-color: #e0e7ff; color: #4338ca; }
        .sidebar-link.active { background-color: #e0e7ff; color: #4338ca; font-weight: bold; }
    </style>
</head>
<body class="flex h-screen overflow-hidden bg-gray-50">

    <!-- الشريط الجانبي (Sidebar) -->
    <aside class="w-64 bg-white shadow-xl hidden md:flex flex-col z-10 border-l border-gray-100">
        <div class="p-6 bg-indigo-600 text-center shadow-lg">
            <div class="w-16 h-16 bg-white rounded-full mx-auto flex items-center justify-center mb-3 shadow-md">
                <i class="fas fa-user-graduate text-3xl text-indigo-600"></i>
            </div>
            <h2 class="text-xl font-bold text-white">لوحة الطالب</h2>
        </div>
        
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <a href="{{ route('student.dashboard') }}" class="sidebar-link active flex items-center p-3 rounded-xl transition duration-200">
                <i class="fas fa-home ml-3 text-lg"></i> الرئيسية
            </a>
            <a href="{{ route('student.my-activities') }}" class="sidebar-link flex items-center p-3 text-gray-600 rounded-xl transition duration-200">
                <i class="fas fa-calendar-check ml-3 text-lg"></i> أنشطتي
            </a>
            <a href="{{ route('student.favorites') }}" class="sidebar-link flex items-center p-3 text-gray-600 rounded-xl transition duration-200">
                <i class="fas fa-heart ml-3 text-lg"></i> المفضلة
            </a>
            <a href="{{ route('student.profile') }}" class="sidebar-link flex items-center p-3 text-gray-600 rounded-xl transition duration-200">
    <i class="fas fa-user ml-3 text-lg"></i> ملفي الشخصي
</a>
<a href="{{ route('profile.edit') }}" class="sidebar-link flex items-center p-3 text-gray-600 rounded-xl transition duration-200">
    <i class="fas fa-cog ml-3 text-lg"></i> الإعدادات
</a>
        </nav>

        <div class="p-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center p-2 text-red-500 hover:bg-red-50 rounded-lg transition font-bold">
                    <i class="fas fa-sign-out-alt ml-2"></i> تسجيل الخروج
                </button>
            </form>
        </div>
    </aside>

    <!-- المحتوى الرئيسي -->
    <div class="flex-1 flex flex-col overflow-hidden relative">
        
        <header class="bg-white shadow-sm p-4 flex justify-between items-center">
            <h1 class="font-bold text-xl text-indigo-700 md:hidden">الطالب</h1>
            <div class="flex items-center gap-4 mr-auto">
                <span class="text-gray-500 bg-gray-50 px-4 py-2 rounded-full text-sm shadow-sm border border-gray-100">
                    <i class="fas fa-calendar-alt ml-1 text-indigo-500"></i> {{ now()->format('Y/m/d') }}
                </span>
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <span class="text-gray-700 font-semibold hidden md:inline">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-6xl mx-auto">
                
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-800">
                        مرحباً بك يا {{ auth()->user()->name }} 🎓
                    </h1>
                    <p class="text-gray-600 mt-2">تصفح أنشطتك ومتابعة تقدمك</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gradient-to-r from-pink-500 to-rose-600 p-6 rounded-2xl shadow-lg text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-pink-100 text-sm mb-1">المفضلة</p>
                                <h3 class="text-4xl font-bold">{{ auth()->user()->favorites()->count() }}</h3>
                            </div>
                            <div class="bg-white/20 p-4 rounded-full">
                                <i class="fas fa-heart text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 p-6 rounded-2xl shadow-lg text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-yellow-100 text-sm mb-1">مجموع النقاط</p>
                                <h3 class="text-4xl font-bold">{{ auth()->user()->points ?? 0 }}</h3>
                            </div>
                            <div class="bg-white/20 p-4 rounded-full">
                                <i class="fas fa-star text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 rounded-2xl shadow-lg text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-indigo-100 text-sm mb-1">الأنشطة المشتركة</p>
                                <h3 class="text-4xl font-bold">{{ auth()->user()->activities()->count() }}</h3>
                            </div>
                            <div class="bg-white/20 p-4 rounded-full">
                                <i class="fas fa-calendar-check text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('activities.index') }}" class="inline-block bg-indigo-600 text-white px-8 py-4 rounded-xl hover:bg-indigo-700 transition font-bold shadow-lg">
                        <i class="fas fa-search ml-2"></i>
                        تصفح الأنشطة
                    </a>
                    <a href="{{ route('student.my-activities') }}" class="inline-block bg-white border-2 border-indigo-600 text-indigo-600 px-8 py-4 rounded-xl hover:bg-indigo-50 transition font-bold">
                        <i class="fas fa-calendar-alt ml-2"></i>
                        أنشطتي المسجلة
                    </a>
                </div>

            </div>
        </main>
    </div>

</body>
</html>