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
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            overflow: hidden; /* ⛔ منع التمرير للصفحة ككل */
        }

        /* ✅ تنسيق شريط التمرير للقائمة الجانبية */
        .sidebar-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: #0f172a; 
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #334155; 
            border-radius: 10px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #475569; 
        }

        /* ✅ تنسيق شريط التمرير للمحتوى الرئيسي */
        .content-scroll::-webkit-scrollbar {
            width: 8px;
        }
        .content-scroll::-webkit-scrollbar-track {
            background: #f1f5f9; 
        }
        .content-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 4px;
        }
        .content-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }
        /* ============================================ */
/* 🌙 Dark Mode Styles - باستخدام Tailwind + CSS Variables */
/* ============================================ */

/* الألوان الأساسية للوضع الداكن */
html.dark {
    color-scheme: dark;
}

html.dark body {
    background-color: #0f172a !important; /* slate-900 */
    color: #f1f5f9 !important; /* slate-100 */
}

/* البطاقات والجداول */
html.dark .bg-white,
html.dark .stat-card,
html.dark .table-container,
html.dark .advanced-filters,
html.dark .modal-content-large,
html.dark .modal-content-small {
    background-color: #1e293b !important; /* slate-800 */
    border-color: #334155 !important; /* slate-700 */
}

/* النصوص */
html.dark .text-slate-800,
html.dark .text-slate-900,
html.dark .text-slate-700 {
    color: #f1f5f9 !important;
}

html.dark .text-slate-500,
html.dark .text-slate-600 {
    color: #94a3b8 !important; /* slate-400 */
}

/* حقول الإدخال */
html.dark input,
html.dark select,
html.dark textarea {
    background-color: #334155 !important;
    border-color: #475569 !important;
    color: #f1f5f9 !important;
}

html.dark input::placeholder {
    color: #94a3b8 !important;
}

/* الجداول */
html.dark .students-table th {
    background-color: #334155 !important;
    color: #f1f5f9 !important;
}

html.dark .students-table tbody tr:hover {
    background-color: #334155 !important;
}

/* البطاقات الإحصائية */
html.dark .stat-number {
    color: #f1f5f9 !important;
}

/* الأزرار الثانوية */
html.dark .btn-modal-secondary,
html.dark .btn-cancel {
    background-color: #334155 !important;
    color: #f1f5f9 !important;
    border-color: #475569 !important;
}

html.dark .btn-modal-secondary:hover,
html.dark .btn-cancel:hover {
    background-color: #475569 !important;
}

/* بطاقات التفاصيل */
html.dark .detail-card,
html.dark .activity-item {
    background-color: #334155 !important;
}

/* القوائم والـ Badges */
html.dark .role-badge.admin {
    background-color: #7f1d1d !important; /* red-900 */
    color: #fecaca !important;
}

html.dark .role-badge.student {
    background-color: #1e3a8a !important; /* blue-900 */
    color: #bfdbfe !important;
}

/* الفلاتر */
html.dark .advanced-filters {
    background-color: #1e293b !important;
    border-color: #334155 !important;
}

/* الرسوم البيانية - خلفية */
html.dark canvas {
    /* ملاحظة: Chart.js يحتاج إعداد خاص للألوان */
}

/* تأثيرات hover */
html.dark .hover\:bg-slate-50:hover {
    background-color: #334155 !important;
}

html.dark .hover\:bg-blue-50:hover {
    background-color: #1e3a8a !important;
}

html.dark .hover\:bg-green-50:hover {
    background-color: #064e3b !important;
}

/* Modal footer */
html.dark .modal-footer {
    background-color: #1e293b !important;
    border-color: #334155 !important;
}

/* Scrollbar للوضع الداكن */
html.dark ::-webkit-scrollbar-track {
    background: #0f172a;
}

html.dark ::-webkit-scrollbar-thumb {
    background: #475569;
}

html.dark ::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}

/* تحسينات إضافية */
html.dark .shadow-sm,
html.dark .shadow,
html.dark .shadow-lg {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.15) !important;
}

/* Transition ناعم للتبديل */
* {
    transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
}
    </style>
    
    @stack('styles')
</head>
<body class="bg-[#f8f6f0] text-slate-800">

    <!-- 🟦 الحاوية الأب: تأخذ ارتفاع الشاشة وتمنع التمرير الخارجي -->
    <div class="flex h-screen overflow-hidden">
        
        <!-- 🟧 القسم 1: القائمة الجانبية (Sidebar) -->
        <aside class="w-64 bg-[#0f172a] text-white h-full flex flex-col border-l border-slate-800 shadow-xl shrink-0">
            
            <!-- شعار القائمة -->
            <div class="h-20 flex items-center justify-center border-b border-slate-800 bg-[#0f172a] shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-[#0f172a] font-bold shadow-lg shadow-amber-500/20">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-amber-500 leading-none">لوحة الإدارة</h1>
                        <span class="text-xs text-slate-400">نظام الأنشطة الطلابية</span>
                    </div>
                </div>
            </div>

            <!-- روابط القائمة (مع شريط تمرير مستقل) -->
            <nav class="flex-1 overflow-y-auto py-6 space-y-2 px-3 sidebar-scroll">
                
                <!-- 1. الرئيسية -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-home w-6 text-center text-lg"></i>
                    <span class="font-medium">الرئيسية</span>
                </a>

                <!-- 2. إدارة الطلاب -->
                <a href="{{ route('admin.students') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.students') ? 'bg-amber-500 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-user-graduate w-6 text-center text-lg"></i>
                    <span class="font-medium">إدارة الطلاب</span>
                </a>

                <!-- 3. إدارة المشرفين -->
                <a href="{{ route('admin.staff.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.staff*') ? 'bg-amber-500 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-user-tie w-6 text-center text-lg"></i>
                    <span class="font-medium">إدارة المشرفين</span>
                </a>

                <!-- 4. إدارة الأنشطة -->
                <a href="{{ route('activities.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('activities.*') ? 'bg-amber-500 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-calendar-check w-6 text-center text-lg"></i>
                    <span class="font-medium">إدارة الأنشطة</span>
                </a>

                <!-- 5. إدارة الاستبيان -->
                <a href="{{ route('admin.survey-questions.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.survey-questions*') ? 'bg-amber-500 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-poll w-6 text-center text-lg"></i>
                    <span class="font-medium">إدارة الاستبيان</span>
                </a>

                <!-- 6. إدارة التسجيلات -->
                <a href="{{ route('admin.all-registrations') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.all-registrations') ? 'bg-amber-500 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-clipboard-list w-6 text-center text-lg"></i>
                    <span class="font-medium">إدارة التسجيلات</span>
                </a>

                <!-- 7. الإعلانات -->
                <a href="{{ route('admin.announcements') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.announcements*') ? 'bg-amber-500 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-bullhorn w-6 text-center text-lg"></i>
                    <span class="font-medium">الإعلانات</span>
                </a>

                <!-- 8. احصائيات -->
                <a href="{{ route('admin.survey-stats.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.survey-stats*') ? 'bg-amber-500 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-chart-bar w-6 text-center text-lg"></i>
                    <span class="font-medium">احصائيات</span>
                </a>

                <!-- 9. إدارة الملف الشخصي -->
                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('profile.*') ? 'bg-amber-500 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-user-cog w-6 text-center text-lg"></i>
                    <span class="font-medium">إدارة الملف الشخصي</span>
                </a>

            </nav>

            <!-- زر الخروج -->
            <div class="p-4 border-t border-slate-800 bg-[#0f172a] shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-3 bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white py-3 rounded-xl transition-all text-sm font-bold border border-red-500/20">
                        <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                    </button>
                </form>
            </div>
        </aside>

        <!-- 🟨 القسم 2: المحتوى الرئيسي (Main Content) -->
        <main class="flex-1 h-full overflow-y-auto content-scroll relative">
            
            <!-- ✅ تم حذف الشريط العلوي (Header) -->
            
            <!-- منطقة المحتوى المتغير -->
            <div class="p-6 md:p-8">
                @yield('content')
            </div>

        </main>

    </div>

@push('scripts')
<script>
// ✅ دالة تبديل الوضع الليلي
function toggleDarkMode() {
    const html = document.documentElement;
    const icon = document.getElementById('darkModeIcon');
    const text = document.getElementById('darkModeText');
    
    if (html.classList.contains('dark')) {
        // تحويل للوضع الفاتح
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
        icon.className = 'fas fa-moon';
        text.textContent = 'الوضع الليلي';
    } else {
        // تحويل للوضع الداكن
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
        icon.className = 'fas fa-sun';
        text.textContent = 'الوضع الفاتح';
    }
}

// ✅ تطبيق الإعداد المحفوظ عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    const html = document.documentElement;
    const icon = document.getElementById('darkModeIcon');
    const text = document.getElementById('darkModeText');
    const savedTheme = localStorage.getItem('theme');
    
    // إذا فيه إعداد محفوظ، طبقه
    if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        html.classList.add('dark');
        icon.className = 'fas fa-sun';
        text.textContent = 'الوضع الفاتح';
    } else {
        html.classList.remove('dark');
        icon.className = 'fas fa-moon';
        text.textContent = 'الوضع الليلي';
    }
});
</script>
@endpush

    @stack('scripts')
</body>
</html>