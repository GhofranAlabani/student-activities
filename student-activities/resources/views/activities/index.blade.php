@extends('layouts.admin')

@section('content')

<!-- Header (Top Bar) - مطابق لإدارة الطلاب -->
<header class="bg-[#0f172a] rounded-2xl p-4 mb-8 shadow-lg flex justify-between items-center text-white relative overflow-hidden">
    
    <!-- اليمين: العنوان -->
    <div class="flex items-center gap-3 z-10">
        <i class="fas fa-calendar-check text-amber-500 text-3xl"></i>
        <div>
            <h1 class="text-2xl font-bold text-white mb-1">إدارة الأنشطة</h1>
            <p class="text-slate-400 text-sm">عرض وإضافة وتعديل الأنشطة والفعاليات الطلابية</p>
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

<!-- بطاقات الإحصائيات -->
<div class="stats-cards">
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-info">
            <h3>إجمالي الأنشطة</h3>
            <p class="stat-number">{{ $activities->count() ?? 0 }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3>الأنشطة المتاحة</h3>
            <p class="stat-number">{{ $availableActivities ?? 0 }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-info">
            <h3>تسجيلاتي</h3>
            <p class="stat-number">{{ auth()->user()->activities()->count() ?? 0 }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-info">
            <h3>التقييم العام</h3>
            <p class="stat-number">4.8</p>
        </div>
    </div>
</div>

<!-- حاوية الجدول -->
<div class="table-container">
    <!-- شريط البحث والفلترة -->
    <div class="table-header">
        <h2 class="table-title">قائمة الأنشطة</h2>
        <a href="{{ route('activities.create') }}" class="btn-add">
            <i class="fas fa-plus"></i> إضافة نشاط
        </a>
    </div>

    <!-- الفلاتر المتقدمة -->
    <div class="advanced-filters">
        <form method="GET" action="{{ url()->current() }}" id="filterForm">
            <div class="filters-grid">
                <!-- حقل البحث -->
                <div class="filter-group">
                    <label><i class="fas fa-search"></i> البحث</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث باسم النشاط...">
                </div>

                <!-- فلتر الفئة -->
                <div class="filter-group">
                    <label><i class="fas fa-filter"></i> الفئة</label>
                    <select name="category">
                        <option value="">جميع الفئات</option>
                        <option value="academic" {{ request('category') == 'academic' ? 'selected' : '' }}>أكاديمية</option>
                        <option value="recreational" {{ request('category') == 'recreational' ? 'selected' : '' }}>ترفيهية</option>
                        <option value="technical" {{ request('category') == 'technical' ? 'selected' : '' }}>تقنية</option>
                    </select>
                </div>

                <!-- فلتر الفترة -->
                <div class="filter-group">
                    <label><i class="fas fa-calendar"></i> الفترة</label>
                    <select name="period">
                        <option value="">جميع الفترات</option>
                        <option value="upcoming" {{ request('period') == 'upcoming' ? 'selected' : '' }}>قادمة</option>
                        <option value="past" {{ request('period') == 'past' ? 'selected' : '' }}>سابقة</option>
                    </select>
                </div>

                <!-- زر البحث -->
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-search">
                        <i class="fas fa-search"></i> بحث
                    </button>
                </div>

                <!-- زر إعادة تعيين -->
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <a href="{{ url()->current() }}" class="btn-reset">
                        <i class="fas fa-redo"></i> إعادة تعيين
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- الجدول -->
    <div class="table-responsive">
        <table class="students-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>النشاط</th>
                    <th>الفئة</th>
                    <th>التاريخ</th>
                    <th>المكان</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities ?? [] as $index => $activity)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="user-info">
                            <div class="avatar" style="background: #6d28d9">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <div class="user-name">{{ $activity->title ?? 'غير محدد' }}</div>
                                <div class="user-phone"><i class="fas fa-map-marker-alt"></i> {{ $activity->location ?? 'غير محدد' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="role-badge student">
                            {{ $activity->category ?? 'عام' }}
                        </span>
                    </td>
                    <td>{{ $activity->date ? \Carbon\Carbon::parse($activity->date)->format('Y/m/d') : 'غير محدد' }}</td>
                    <td>{{ $activity->location ?? 'غير محدد' }}</td>
                    <td>
                        @if($activity->is_active)
                            <span class="status-badge active">
                                <i class="fas fa-check-circle"></i> نشط
                            </span>
                        @else
                            <span class="status-badge inactive">
                                <i class="fas fa-times-circle"></i> غير نشط
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="actions-buttons">
                            <a href="{{ route('activities.show', $activity->id) }}" class="action-btn view" title="عرض">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('activities.edit', $activity->id) }}" class="action-btn edit" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا النشاط؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="no-data">
                        <i class="fas fa-inbox"></i>
                        <p>لا توجد أنشطة مسجلة حالياً</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

<style>
    /* نفس الـ CSS اللي في صفحة الطلاب */
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
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
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

    .stat-icon.purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
    .stat-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-icon.blue { background: linear-gradient(135deg, #3b82f6, #1e40af); }
    .stat-icon.orange { background: linear-gradient(135deg, #f97316, #ea580c); }

    .stat-info h3 {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .stat-number {
        font-size: 32px;
        font-weight: 900;
        color: #1e3a8a;
        margin: 0;
        line-height: 1;
    }

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

    .btn-add {
        padding: 10px 20px;
        background: #4f46e5;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-add:hover {
        background: #4338ca;
    }

    .advanced-filters {
        background: #f9fafb;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #e5e7eb;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-group input,
    .filter-group select {
        padding: 10px 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: border-color 0.3s;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #4f46e5;
    }

    .btn-search,
    .btn-reset {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-search {
        background: #4f46e5;
        color: white;
    }

    .btn-search:hover {
        background: #4338ca;
    }

    .btn-reset {
        background: #6b7280;
        color: white;
    }

    .btn-reset:hover {
        background: #4b5563;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .students-table {
        width: 100%;
        border-collapse: collapse;
    }

    .students-table th,
    .students-table td {
        padding: 14px 12px;
        text-align: right;
        border-bottom: 1px solid #e5e7eb;
    }

    .students-table th {
        background: #f9fafb;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }

    .students-table tbody tr:hover {
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
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
        flex-shrink: 0;
    }

    .user-name {
        font-weight: 600;
        color: #111827;
    }

    .user-phone {
        font-size: 12px;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .role-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .role-badge.student {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .status-badge.active {
        background: #dcfce7;
        color: #166534;
    }

    .status-badge.inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .actions-buttons {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        text-decoration: none;
    }

    .action-btn.view {
        background: #dbeafe;
        color: #2563eb;
    }

    .action-btn.view:hover {
        background: #2563eb;
        color: white;
    }

    .action-btn.edit {
        background: #fef3c7;
        color: #d97706;
    }

    .action-btn.edit:hover {
        background: #d97706;
        color: white;
    }

    .action-btn.delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .action-btn.delete:hover {
        background: #dc2626;
        color: white;
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
</style>

<script>
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