<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة تحكم الإدارة')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts - Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #0f172a; 
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155; 
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #475569; 
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">

    <div class="flex min-h-screen">
        
        <!-- Sidebar (القائمة الجانبية) -->
        <aside class="w-64 bg-[#0f172a] text-white h-screen sticky top-0 flex flex-col border-l border-slate-800 shadow-2xl z-30 fixed right-0 transition-all duration-300">
            
            <!-- Logo Area -->
            <div class="h-20 flex items-center justify-center border-b border-slate-800 bg-[#0f172a]">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-[#0f172a] font-bold text-lg shadow-lg shadow-amber-500/20">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-amber-500 leading-none">لوحة الإدارة</h1>
                        <span class="text-xs text-slate-400">نظام الأنشطة الطلابية</span>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto py-6 space-y-2 px-3 custom-scrollbar">
                
                <!-- الرئيسية -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-home w-6 text-center text-lg"></i>
                    <span class="font-medium">الرئيسية</span>
                </a>

                <!-- إدارة الطلاب -->
                <a href="{{ route('admin.students') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.students') ? 'bg-amber-500 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-user-graduate w-6 text-center text-lg"></i>
                    <span class="font-medium">إدارة الطلاب</span>
                </a>

                <!-- إدارة الأنشطة -->
                <a href="{{ route('activities.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('activities.*') ? 'bg-amber-500 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-calendar-check w-6 text-center text-lg"></i>
                    <span class="font-medium">إدارة الأنشطة</span>
                </a>

                <!-- إدارة التسجيلات -->
                <a href="{{ route('admin.all-registrations') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.all-registrations') ? 'bg-amber-500 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-clipboard-list w-6 text-center text-lg"></i>
                    <span class="font-medium">التسجيلات</span>
                </a>

                <!-- الإعلانات والتبليغات -->
                <a href="{{ route('admin.announcements') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.announcements') ? 'bg-amber-500 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-bullhorn w-6 text-center text-lg"></i>
                    <span class="font-medium">الإعلانات والتبليغات</span>
                </a>

                <!-- التقارير -->
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-slate-400 hover:bg-slate-800 hover:text-white">
                    <i class="fas fa-chart-bar w-6 text-center text-lg"></i>
                    <span class="font-medium">التقارير والإحصائيات</span>
                </a>

                <!-- الإعدادات -->
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-slate-400 hover:bg-slate-800 hover:text-white">
                    <i class="fas fa-cog w-6 text-center text-lg"></i>
                    <span class="font-medium">الإعدادات العامة</span>
                </a>

            </nav>

            <!-- Footer / Logout -->
            <div class="p-4 border-t border-slate-800 bg-[#0f172a]">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-3 bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white py-3 rounded-xl transition-all text-sm font-bold border border-red-500/20">
                        <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                    </button>
                </form>
            </div>
        </aside>

           <!-- Main Content Area -->
    <!-- التعديل هنا: استخدام flex-1 لملء الفراغ، وإزالة الهوامش الزائدة (mr-64 إلى mr-0 أو ضبطها بدقة) -->
    <main class="flex-1 bg-[#f8f6f0] min-h-screen transition-all duration-300 overflow-x-hidden">
        <!-- محتوى الصفحة هنا -->
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>