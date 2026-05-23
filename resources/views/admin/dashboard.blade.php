<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المدير</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- خط Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- أيقونات Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #f8fafc; }
        .sidebar-link:hover { background-color: #e0e7ff; color: #4338ca; }
        .sidebar-link.active { background-color: #e0e7ff; color: #4338ca; font-weight: bold; }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <!-- القائمة الجانبية (Sidebar) -->
    <aside class="w-64 bg-white shadow-xl hidden md:flex flex-col z-10 border-l border-gray-100">
        <div class="p-6 bg-indigo-600 text-center shadow-lg">
            <div class="w-16 h-16 bg-white rounded-full mx-auto flex items-center justify-center mb-3 shadow-md">
                <i class="fas fa-user-shield text-3xl text-indigo-600"></i>
            </div>
            <h2 class="text-xl font-bold text-white">لوحة المدير</h2>
        </div>
        
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link active flex items-center p-3 rounded-xl transition duration-200">
                <i class="fas fa-home ml-3 text-lg"></i> الرئيسية
            </a>
            <a href="{{ route('activities.index') }}" class="sidebar-link flex items-center p-3 text-gray-600 rounded-xl transition duration-200">
                <i class="fas fa-calendar-alt ml-3 text-lg"></i> الأنشطة
            </a>
            <a href="#" class="sidebar-link flex items-center p-3 text-gray-600 rounded-xl transition duration-200">
                <i class="fas fa-users ml-3 text-lg"></i> الطلاب
            </a>
            <a href="#" class="sidebar-link flex items-center p-3 text-gray-600 rounded-xl transition duration-200">
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
        
        <!-- الهيدر العلوي -->
        <header class="bg-white shadow-sm p-4 flex justify-between items-center">
            <h1 class="font-bold text-xl text-indigo-700 md:hidden">المدير</h1>
            <div class="flex items-center gap-4 mr-auto">
                <span class="text-gray-500 bg-gray-50 px-4 py-2 rounded-full text-sm shadow-sm border border-gray-100">
                    <i class="fas fa-calendar-alt ml-1 text-indigo-500"></i> {{ now()->format('Y/m/d') }}
                </span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 md:p-10">
            <div class="max-w-7xl mx-auto">
                
                <!-- عنوان الترحيب -->
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-extrabold text-gray-800">مرحباً بك في لوحة التحكم 👋</h1>
                </div>

                <!-- بطاقات الإحصائيات (قابلة للنقر) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    
                    <!-- بطاقة الأنشطة -->
                    <a href="{{ route('activities.index') }}" class="block bg-white p-6 rounded-2xl shadow-md border-b-4 border-indigo-500 hover:shadow-xl transition transform hover:-translate-y-1 cursor-pointer">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-500 font-medium mb-1">مجموع الأنشطة</p>
                                <h3 class="text-4xl font-extrabold text-gray-800">{{ $totalActivities }}</h3>
                            </div>
                            <div class="bg-indigo-100 p-4 rounded-full text-indigo-600 text-2xl shadow-inner">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                    </a>

                    <!-- بطاقة الطلاب -->
                    <a href="#" class="block bg-white p-6 rounded-2xl shadow-md border-b-4 border-green-500 hover:shadow-xl transition transform hover:-translate-y-1 cursor-pointer">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-500 font-medium mb-1">عدد الطلاب</p>
                                <h3 class="text-4xl font-extrabold text-gray-800">{{ $totalStudents }}</h3>
                            </div>
                            <div class="bg-green-100 p-4 rounded-full text-green-600 text-2xl shadow-inner">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                        </div>
                    </a>

                    <!-- بطاقة التسجيلات -->
                    <a href="#" class="block bg-white p-6 rounded-2xl shadow-md border-b-4 border-yellow-500 hover:shadow-xl transition transform hover:-translate-y-1 cursor-pointer">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-500 font-medium mb-1">إجمالي التسجيلات</p>
                                <h3 class="text-4xl font-extrabold text-gray-800">{{ $totalRegistrations }}</h3>
                            </div>
                            <div class="bg-yellow-100 p-4 rounded-full text-yellow-600 text-2xl shadow-inner">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                        </div>
                    </a>

                </div>

                <!-- الإجراءات السريعة -->
                <div class="bg-white rounded-2xl shadow-md p-8 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-4">⚡ إجراءات سريعة</h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('activities.index') }}" class="flex items-center bg-indigo-600 text-white px-8 py-4 rounded-xl hover:bg-indigo-700 transition shadow-lg hover:shadow-indigo-500/30 transform hover:-translate-y-1">
                            <i class="fas fa-eye ml-3 text-xl"></i> 
                            <span class="font-bold">تصفح الأنشطة</span>
                        </a>
                        
                        <a href="#" class="flex items-center bg-white border-2 border-indigo-600 text-indigo-600 px-8 py-4 rounded-xl hover:bg-indigo-50 transition transform hover:-translate-y-1">
                            <i class="fas fa-plus ml-3 text-xl"></i> 
                            <span class="font-bold">إضافة نشاط جديد</span>
                        </a>
                    </div>
                </div>

            </div>
        </main>
    </div>

</body>
</html>