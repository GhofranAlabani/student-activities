@extends('layouts.admin')

@section('content')

<!-- Header (Top Bar) -->
<header class="bg-[#0f172a] rounded-2xl p-4 mb-8 shadow-lg flex justify-between items-center text-white relative overflow-hidden">
    
    <!-- اليمين: العنوان -->
    <div class="flex items-center gap-3 z-10">
        <i class="fas fa-user-cog text-amber-500 text-3xl"></i>
        <div>
            <h1 class="text-2xl font-bold text-white mb-1">الملف الشخصي</h1>
            <p class="text-slate-400 text-sm">إدارة بيانات حسابك وتغيير كلمة المرور</p>
        </div>
    </div>

    <!-- اليسار: التاريخ + زر الوضع الليلي -->
    <div class="flex flex-col items-end gap-2 z-10">
        
        <!-- التاريخ -->
        <div class="bg-slate-800 px-4 py-2 rounded-xl border border-slate-700 flex items-center gap-2">
            <span class="font-semibold text-sm">{{ now()->format('d/m/Y') }}</span>
            <i class="far fa-calendar-alt text-amber-500"></i>
        </div>

        <!-- زر الوضع الليلي -->
        <button onclick="toggleDarkMode()" 
                class="w-9 h-9 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-400 hover:text-amber-500 hover:border-amber-500 transition-all shadow-md"
                title="تبديل الوضع الليلي">
            <i class="fas fa-moon text-xs" id="darkModeIcon"></i>
        </button>

    </div>
</header>

<!-- رسائل النجاح والخطأ -->
@if(session('success'))
<div class="fixed top-4 left-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 animate-pulse flex items-center gap-2">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="fixed top-4 left-4 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg z-50">
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
    </ul>
</div>
@endif

<!-- المحتوى الرئيسي - نموذجين في وسط الصفحة -->
<div class="max-w-4xl mx-auto space-y-8">
    
    <!-- بطاقة المعلومات الشخصية -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 dark:bg-slate-800 dark:border-slate-700 transition-colors">
        <h3 class="text-2xl font-bold text-slate-800 mb-8 flex items-center gap-3 dark:text-white">
            <i class="fas fa-user text-amber-500"></i>
            المعلومات الشخصية
        </h3>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- الاسم الكامل -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2 dark:text-slate-300">الاسم الكامل</label>
                    <div class="relative">
                        <i class="fas fa-user absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" required
                               class="w-full pr-10 pl-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:placeholder-slate-400">
                    </div>
                </div>

                <!-- البريد الإلكتروني -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2 dark:text-slate-300">البريد الإلكتروني</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" required
                               class="w-full pr-10 pl-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:placeholder-slate-400">
                    </div>
                </div>

                <!-- الصلاحية (للعرض فقط) -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2 dark:text-slate-300">الصلاحية</label>
                    <div class="flex items-center gap-2 py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl dark:bg-slate-700 dark:border-slate-600">
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ auth()->user()->role === 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                            {{ auth()->user()->role === 'admin' ? '👨‍💼 مدير النظام' : '🎓 مشرف' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- زر الحفظ -->
            <div class="flex justify-end pt-6 border-t border-slate-100 dark:border-slate-700">
                <button type="submit" class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold transition shadow-lg shadow-amber-500/20 flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>

    <!-- بطاقة تغيير كلمة المرور -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 dark:bg-slate-800 dark:border-slate-700 transition-colors">
        <h3 class="text-2xl font-bold text-slate-800 mb-8 flex items-center gap-3 dark:text-white">
            <i class="fas fa-lock text-red-500"></i>
            تغيير كلمة المرور
        </h3>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="update_password" value="1">

            <div class="space-y-6">
                <!-- كلمة المرور الحالية -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2 dark:text-slate-300">كلمة المرور الحالية</label>
                    <div class="relative">
                        <i class="fas fa-key absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="password" name="current_password" required
                               class="w-full pr-10 pl-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:placeholder-slate-400" placeholder="••••••••">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- كلمة المرور الجديدة -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2 dark:text-slate-300">كلمة المرور الجديدة</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="password" name="password" required
                                   class="w-full pr-10 pl-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:placeholder-slate-400" placeholder="••••••••">
                        </div>
                    </div>

                    <!-- تأكيد كلمة المرور -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2 dark:text-slate-300">تأكيد كلمة المرور</label>
                        <div class="relative">
                            <i class="fas fa-check-double absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="password" name="password_confirmation" required
                                   class="w-full pr-10 pl-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:placeholder-slate-400" placeholder="••••••••">
                        </div>
                    </div>
                </div>
            </div>

            <!-- زر التحديث -->
            <div class="flex justify-end pt-6 mt-6 border-t border-slate-100 dark:border-slate-700">
                <button type="submit" class="px-8 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold transition shadow-lg shadow-red-500/20 flex items-center gap-2">
                    <i class="fas fa-shield-alt"></i>
                    تحديث كلمة المرور
                </button>
            </div>
        </form>
    </div>

</div>

@endsection

<!-- كود الجافاسكربت للوضع الليلي -->
<script>
function toggleDarkMode() {
    const html = document.documentElement;
    const icon = document.getElementById('darkModeIcon');
    
    if (html.classList.contains('dark')) {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
        icon.className = 'fas fa-moon text-xs';
    } else {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
        icon.className = 'fas fa-sun text-xs';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const html = document.documentElement;
    const icon = document.getElementById('darkModeIcon');
    const savedTheme = localStorage.getItem('theme');
    
    if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        html.classList.add('dark');
        icon.className = 'fas fa-sun text-xs';
    }
});
</script>