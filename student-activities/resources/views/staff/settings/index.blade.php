<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الإعدادات - لوحة المشرف</title>
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
        .sidebar-link { transition: all 0.3s ease; }
        .sidebar-link:hover { background-color: rgba(212, 160, 23, 0.1); transform: translateX(-5px); }
        .sidebar-link.active { background-color: rgba(212, 160, 23, 0.2); border-right: 3px solid #d4a017; }
        .tab-btn.active { background-color: #d4a017; color: #0a1929; }
    </style>
</head>
<body class="bg-[#f5f0e8] min-h-screen">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-72 bg-navy text-white fixed right-0 top-0 h-full shadow-2xl z-50 overflow-y-auto">
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gold rounded-xl flex items-center justify-center text-navy font-black text-xl shadow-lg">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gold">لوحة المشرف</h2>
                        <p class="text-xs text-gray-400">{{ auth()->user()->name }}</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-2">
                <a href="{{ route('staff.dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-home text-gold w-5"></i>
                    <span class="font-bold">الرئيسية</span>
                </a>
                <a href="{{ route('staff.activities.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-calendar-alt text-gold w-5"></i>
                    <span class="font-bold">أنشطتي</span>
                </a>
                <a href="{{ route('staff.activities.create') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-plus-circle text-gold w-5"></i>
                    <span class="font-bold">إضافة نشاط</span>
                </a>
                <a href="{{ route('staff.students.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-users text-gold w-5"></i>
                    <span class="font-bold">الطلاب المسجلين</span>
                </a>
                <a href="{{ route('staff.reports.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-chart-bar text-gold w-5"></i>
                    <span class="font-bold">التقارير</span>
                </a>
                <a href="{{ route('staff.announcements.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-bullhorn text-gold w-5"></i>
                    <span class="font-bold">الإعلانات</span>
                </a>
                <div class="border-t border-white/10 my-4"></div>
                <a href="{{ route('staff.settings.index') }}" class="sidebar-link active flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-cog text-gold w-5"></i>
                    <span class="font-bold">الإعدادات</span>
                </a>
            </nav>

            <div class="absolute bottom-0 right-0 left-0 p-4 border-t border-white/10 bg-navy-light">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-400 hover:bg-red-500/10 transition">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="font-bold">تسجيل الخروج</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 mr-72">
            
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 sticky top-0 z-40">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-black text-navy">الإعدادات</h1>
                        <p class="text-sm text-gray-500 mt-1">إدارة حسابك وتفضيلاتك</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-left">
                            <p class="font-bold text-navy">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">مشرف أنشطة</p>
                        </div>
                        <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center text-navy font-bold shadow-lg">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-8">
                
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Tabs -->
                <div class="flex gap-2 mb-6 bg-white p-2 rounded-xl shadow-sm w-fit">
                    <button onclick="showTab('profile')" id="tab-profile" class="tab-btn active px-6 py-2 rounded-lg font-bold transition">
                        <i class="fas fa-user ml-2"></i> البيانات الشخصية
                    </button>
                    <button onclick="showTab('password')" id="tab-password" class="tab-btn px-6 py-2 rounded-lg font-bold text-gray-600 hover:bg-gray-100 transition">
                        <i class="fas fa-lock ml-2"></i> كلمة المرور
                    </button>
                </div>

                <!-- Profile Tab -->
                <div id="content-profile" class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-black text-navy mb-6 flex items-center gap-2">
                        <i class="fas fa-user-edit text-gold"></i>
                        تحديث البيانات الشخصية
                    </h2>

                    <form action="{{ route('staff.settings.profile.update') }}" method="POST" class="space-y-6 max-w-2xl">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-navy font-bold mb-2">الاسم الكامل *</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">
                            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-navy font-bold mb-2">البريد الإلكتروني *</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">
                            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="bg-gold text-navy px-8 py-3 rounded-xl font-bold hover:bg-yellow-600 transition shadow-lg">
                                <i class="fas fa-save ml-2"></i> حفظ التغييرات
                            </button>
                            <button type="reset" class="bg-gray-200 text-gray-700 px-8 py-3 rounded-xl font-bold hover:bg-gray-300 transition">
                                <i class="fas fa-redo ml-2"></i> إعادة تعيين
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Tab -->
                <div id="content-password" class="bg-white rounded-2xl shadow-lg p-8 hidden">
                    <h2 class="text-2xl font-black text-navy mb-6 flex items-center gap-2">
                        <i class="fas fa-key text-gold"></i>
                        تغيير كلمة المرور
                    </h2>

                    <form action="{{ route('staff.settings.password.update') }}" method="POST" class="space-y-6 max-w-2xl">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-navy font-bold mb-2">كلمة المرور الحالية *</label>
                            <input type="password" name="current_password" required
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">
                            @error('current_password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-navy font-bold mb-2">كلمة المرور الجديدة *</label>
                            <input type="password" name="password" required
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">
                            @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-navy font-bold mb-2">تأكيد كلمة المرور الجديدة *</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-xl">
                            <p class="text-yellow-800 text-sm">
                                <i class="fas fa-info-circle ml-1"></i>
                                كلمة المرور يجب أن تكون 8 أحرف على الأقل وتحتوي على أحرف كبيرة وصغيرة وأرقام.
                            </p>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="bg-gold text-navy px-8 py-3 rounded-xl font-bold hover:bg-yellow-600 transition shadow-lg">
                                <i class="fas fa-save ml-2"></i> تغيير كلمة المرور
                            </button>
                        </div>
                    </form>
                </div>

            </main>
        </div>

    </div>

    <script>
        function showTab(tabName) {
            // إخفاء كل المحتوى
            document.getElementById('content-profile').classList.add('hidden');
            document.getElementById('content-password').classList.add('hidden');
            
            // إزالة active من كل الأزرار
            document.getElementById('tab-profile').classList.remove('active');
            document.getElementById('tab-password').classList.remove('active');
            document.getElementById('tab-profile').classList.add('text-gray-600');
            document.getElementById('tab-password').classList.add('text-gray-600');
            
            // إظهار المحتوى المطلوب
            document.getElementById('content-' + tabName).classList.remove('hidden');
            document.getElementById('tab-' + tabName).classList.add('active');
            document.getElementById('tab-' + tabName).classList.remove('text-gray-600');
        }
    </script>

</body>
</html>