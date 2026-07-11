@extends('layouts.admin')

@section('content')

<!-- Header (Top Bar) -->
<header class="bg-[#0f172a] rounded-2xl p-4 mb-8 shadow-lg flex justify-between items-center text-white relative overflow-hidden">
    
    <!-- اليمين: العنوان -->
    <div class="flex items-center gap-3 z-10">
        <i class="fas fa-bullhorn text-amber-500 text-3xl"></i>
        <div>
            <h1 class="text-2xl font-bold text-white mb-1">إدارة الإعلانات</h1>
            <p class="text-slate-400 text-sm">نشر وإدارة الإعلانات والتحديثات للنظام</p>
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

<!-- المحتوى الأصلي -->
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-slate-800 dark:text-white">قائمة الإعلانات</h2>
    <a href="{{ route('admin.announcements.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 transition shadow-lg shadow-indigo-500/20">
        <i class="fas fa-plus"></i> إضافة إعلان جديد
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 dark:bg-slate-800 dark:border-slate-700">
    @if(isset($announcements) && $announcements->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead class="bg-slate-50 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                    <tr>
                        <th class="p-4 rounded-r-xl">#</th>
                        <th class="p-4">العنوان</th>
                        <th class="p-4">النوع</th>
                        <th class="p-4">الحالة</th>
                        <th class="p-4">التاريخ</th>
                        <th class="p-4 rounded-l-xl">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($announcements as $index => $announcement)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                        <td class="p-4 font-bold text-slate-500">{{ $index + 1 }}</td>
                        <td class="p-4 font-semibold text-slate-800 dark:text-white">{{ $announcement->title }}</td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                {{ $announcement->type ?? 'عام' }}
                            </span>
                        </td>
                        <td class="p-4">
                            @if($announcement->is_active)
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">نشط</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">غير نشط</span>
                            @endif
                        </td>
                        <td class="p-4 text-slate-500 dark:text-slate-400">{{ $announcement->created_at->format('Y/m/d') }}</td>
                        <td class="p-4 flex gap-2">
                            <a href="{{ route('admin.announcements.edit', $announcement->id) }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition dark:bg-blue-900/30 dark:text-blue-400" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.announcements.destroy', $announcement->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الإعلان؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition dark:bg-red-900/30 dark:text-red-400" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <!-- رسالة في حال عدم وجود إعلانات -->
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4 dark:bg-slate-700">
                <i class="fas fa-bullhorn text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-700 dark:text-white">لا توجد إعلانات حالياً</h3>
            <p class="text-slate-500 dark:text-slate-400 mt-1">ابدأ بإضافة أول إعلان للنظام</p>
        </div>
    @endif
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