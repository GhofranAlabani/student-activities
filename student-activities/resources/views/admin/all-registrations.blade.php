@extends('layouts.admin')

@section('content')

<!-- Header (Top Bar) - مطابق للوحات الأخرى -->
<header class="bg-[#0f172a] rounded-2xl p-4 mb-8 shadow-lg flex justify-between items-center text-white relative overflow-hidden">
    
    <!-- اليمين: العنوان -->
    <div class="flex items-center gap-3 z-10">
        <i class="fas fa-clipboard-list text-amber-500 text-3xl"></i>
        <div>
            <h1 class="text-2xl font-bold text-white mb-1">إدارة التسجيلات</h1>
            <p class="text-slate-400 text-sm">عرض جميع تسجيلات الطلاب في الأنشطة</p>
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

<!-- رسائل النجاح/الخطأ -->
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 mx-6 flex items-center gap-2 dark:bg-green-900/30 dark:border-green-800 dark:text-green-400">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 mx-6 flex items-center gap-2 dark:bg-red-900/30 dark:border-red-800 dark:text-red-400">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

<!-- بطاقات الإحصائيات -->
<div class="stats-cards">
    <div class="stat-card dark:bg-slate-800 dark:border-slate-700">
        <div class="stat-icon blue">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div class="stat-info">
            <h3 class="dark:text-slate-400">إجمالي التسجيلات</h3>
            <p class="stat-number dark:text-white">{{ $registrations->total() }}</p>
        </div>
    </div>

    <div class="stat-card dark:bg-slate-800 dark:border-slate-700">
        <div class="stat-icon green">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-info">
            <h3 class="dark:text-slate-400">الأنشطة النشطة</h3>
            <p class="stat-number dark:text-white">{{ \App\Models\Activity::count() }}</p>
        </div>
    </div>

    <div class="stat-card dark:bg-slate-800 dark:border-slate-700">
        <div class="stat-icon purple">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3 class="dark:text-slate-400">الطلاب المسجلين</h3>
            <p class="stat-number dark:text-white">{{ \App\Models\User::where('role', 'student')->count() }}</p>
        </div>
    </div>
</div>

<!-- جدول التسجيلات -->
<div class="table-container dark:bg-slate-800 dark:border-slate-700">
    <div class="table-header">
        <h2 class="table-title dark:text-white">قائمة التسجيلات</h2>
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="ابحث باسم الطالب أو النشاط..." class="dark:bg-slate-700 dark:border-slate-600 dark:text-white">
            <button><i class="fas fa-search"></i> بحث</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="registrations-table" id="registrationsTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الطالب</th>
                    <th>البريد الإلكتروني</th>
                    <th>النشاط</th>
                    <th>تاريخ التسجيل</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $registration)
                <tr class="dark:hover:bg-slate-700/50">
                    <td class="dark:text-slate-300">{{ $registration->id }}</td>
                    <td>
                        <div class="user-info">
                            <div class="avatar">{{ substr($registration->student_name, 0, 1) }}</div>
                            <span class="dark:text-white">{{ $registration->student_name }}</span>
                        </div>
                    </td>
                    <td class="dark:text-slate-400">{{ $registration->student_email }}</td>
                    <td>
                        <span class="activity-badge dark:bg-blue-900/30 dark:text-blue-400">
                            <i class="fas fa-calendar-alt"></i>
                            {{ $registration->activity_title }}
                        </span>
                    </td>
                    <td class="dark:text-slate-400">{{ \Carbon\Carbon::parse($registration->created_at)->format('Y/m/d h:i A') }}</td>
                    <td>
                        @php
                            $statusColors = [
                                'مسجل' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'مؤكد' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                'ملغي' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
                            ];
                            $statusClass = $statusColors[$registration->status ?? 'مسجل'] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="status-badge {{ $statusClass }}">
                            <i class="fas fa-check-circle"></i>
                            {{ $registration->status ?? 'مسجل' }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <!-- زر التعديل -->
                            <a href="{{ route('admin.registrations.edit', $registration->id) }}" 
                               class="btn-edit" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <!-- زر الحذف -->
                            <form action="{{ route('admin.registrations.destroy', $registration->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('⚠️ هل أنت متأكد من حذف هذا التسجيل؟\n\nهذا الإجراء لا يمكن التراجع عنه.')"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="no-data dark:text-slate-400">
                        <i class="fas fa-inbox"></i>
                        <p>لا توجد تسجيلات حالياً</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($registrations->hasPages())
    <div class="pagination">
        {{ $registrations->links() }}
    </div>
    @endif
</div>

@endsection

<style>
    .all-registrations {
        padding: 20px;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-header h1 {
        color: #1e3a8a;
        font-size: 28px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-subtitle {
        color: #6b7280;
        font-size: 15px;
    }

    /* بطاقات الإحصائيات */
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 15px;
        font-size: 24px;
        color: white;
    }

    .stat-icon.blue { background: linear-gradient(135deg, #3b82f6, #1e40af); }
    .stat-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-icon.purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }

    .stat-info h3 {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .stat-number {
        font-size: 28px;
        font-weight: bold;
        color: #1e3a8a;
        margin: 0;
    }

    /* الجدول */
    .table-container {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .table-title {
        color: #1e3a8a;
        font-size: 20px;
        margin: 0;
    }

    .search-box {
        display: flex;
        gap: 10px;
        flex: 1;
        max-width: 500px;
    }

    .search-box input {
        flex: 1;
        padding: 12px 15px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: border-color 0.3s;
    }

    .search-box input:focus {
        outline: none;
        border-color: #4f46e5;
    }

    .search-box button {
        padding: 12px 24px;
        background: #4f46e5;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-family: inherit;
        font-size: 14px;
        transition: background 0.3s;
    }

    .search-box button:hover {
        background: #4338ca;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .registrations-table {
        width: 100%;
        border-collapse: collapse;
    }

    .registrations-table th,
    .registrations-table td {
        padding: 14px 12px;
        text-align: right;
        border-bottom: 1px solid #e5e7eb;
    }

    .registrations-table th {
        background: #f9fafb;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }

    .registrations-table tbody tr:hover {
        background: #f9fafb;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #4f46e5;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
    }

    .activity-badge {
        background: #dbeafe;
        color: #1e40af;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .action-buttons a,
    .action-buttons button {
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-edit {
        background: #3b82f6;
        color: white;
        text-decoration: none;
    }

    .btn-edit:hover {
        background: #2563eb;
    }

    .btn-delete {
        background: #ef4444;
        color: white;
    }

    .btn-delete:hover {
        background: #dc2626;
    }

    .no-data {
        text-align: center;
        padding: 40px 20px;
        color: #9ca3af;
    }

    .no-data i {
        font-size: 48px;
        margin-bottom: 10px;
        display: block;
    }

    .pagination {
        margin-top: 20px;
    }

    /* Dark Mode Support */
    html.dark .registrations-table th {
        background-color: #334155 !important;
        color: #f1f5f9 !important;
    }
    
    html.dark .registrations-table tbody tr {
        border-bottom-color: #475569;
    }
</style>

@push('scripts')
<script>
    // البحث في الجدول
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const rows = document.querySelectorAll('#registrationsTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchText) ? '' : 'none';
        });
    });

    // دالة تبديل الوضع الليلي
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

    // تطبيق الإعداد عند التحميل
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
@endpush