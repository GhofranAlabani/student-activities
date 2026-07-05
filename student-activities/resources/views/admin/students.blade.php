@extends('layouts.admin')

@section('content')
<div class="students-management">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <h1><i class="fas fa-users"></i> إدارة الطلاب</h1>
        <p class="page-subtitle">إدارة جميع المستخدمين والطلاب في النظام</p>
    </div>

    <!-- بطاقات الإحصائيات -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>إجمالي المستخدمين</h3>
                <p class="stat-number">{{ $totalUsers ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-info">
                <h3>الطلاب</h3>
                <p class="stat-number">{{ $totalStudents ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="stat-info">
                <h3>المدراء</h3>
                <p class="stat-number">{{ $totalAdmins ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- حاوية الجدول -->
    <div class="table-container">
        <!-- شريط البحث والفلترة -->
        <div class="table-header">
            <h2 class="table-title">قائمة الطلاب</h2>
            
            <!-- أزرار التصدير -->
            <div class="export-buttons">
                <button onclick="exportToExcel()" class="btn-export excel">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                <button onclick="exportToPDF()" class="btn-export pdf">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
            </div>
        </div>

        <!-- الفلاتر المتقدمة -->
        <div class="advanced-filters">
            <form method="GET" action="{{ route('admin.students') }}" id="filterForm">
                <div class="filters-grid">
                    <!-- حقل البحث -->
                    <div class="filter-group">
                        <label><i class="fas fa-search"></i> البحث</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث بالاسم أو البريد...">
                    </div>

                    <!-- فلتر الصلاحية -->
                    <div class="filter-group">
                        <label><i class="fas fa-user-tag"></i> الصلاحية</label>
                        <select name="role" onchange="this.form.submit()">
                            <option value="">الكل</option>
                            <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>طلاب فقط</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>مدراء فقط</option>
                            <option value="moderator" {{ request('role') == 'moderator' ? 'selected' : '' }}>مشرفين</option>
                        </select>
                    </div>

                    <!-- فلتر الحالة -->
                    <div class="filter-group">
                        <label><i class="fas fa-toggle-on"></i> الحالة</label>
                        <select name="status" onchange="this.form.submit()">
                            <option value="">الكل</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>محظور</option>
                        </select>
                    </div>

                    <!-- فلتر النشاط -->
                    <div class="filter-group">
                        <label><i class="fas fa-calendar-alt"></i> النشاط</label>
                        <select name="activity" onchange="this.form.submit()">
                            <option value="">جميع الأنشطة</option>
                            @foreach($activities ?? [] as $activity)
                                <option value="{{ $activity->id }}" {{ request('activity') == $activity->id ? 'selected' : '' }}>
                                    {{ $activity->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- زر إعادة تعيين -->
                    <div class="filter-group">
                        <label>&nbsp;</label>
                        <a href="{{ route('admin.students') }}" class="btn-reset">
                            <i class="fas fa-redo"></i> إعادة تعيين
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- الجدول -->
        <div class="table-responsive">
            <table class="students-table" id="studentsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الصلاحية</th>
                        <th>الأنشطة</th>
                        <th>تاريخ التسجيل</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td>{{ $student->id }}</td>
                        <td>
                            <div class="user-info">
                                <div class="avatar" style="background: {{ $student->role === 'admin' ? '#dc2626' : '#4f46e5' }}">
                                    {{ substr($student->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="user-name">{{ $student->name }}</div>
                                    @if($student->phone)
                                        <div class="user-phone"><i class="fas fa-phone"></i> {{ $student->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $student->email }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.user.role', $student->id) }}" class="role-form">
                                @csrf
                                @method('PATCH')
                                <select name="role" class="role-select" onchange="this.form.submit()">
                                    <option value="student" {{ $student->role === 'student' ? 'selected' : '' }}>
                                        👨‍🎓 طالب
                                    </option>
                                    <option value="admin" {{ $student->role === 'admin' ? 'selected' : '' }}>
                                        👨‍💼 مدير
                                    </option>
                                    <option value="moderator" {{ $student->role === 'moderator' ? 'selected' : '' }}>
                                        👁️ مشرف
                                    </option>
                                </select>
                            </form>
                        </td>
                        <td>
                            @php
                                $activitiesCount = $student->activities()->count();
                            @endphp
                            @if($activitiesCount > 0)
                                <button onclick="openActivitiesModal({{ $student->id }}, '{{ $student->name }}')" 
                                        class="activities-badge clickable">
                                    <i class="fas fa-calendar-check"></i>
                                    {{ $activitiesCount }} نشاط
                                </button>
                            @else
                                <span class="activities-badge">
                                    <i class="fas fa-minus-circle"></i>
                                    لا يوجد
                                </span>
                            @endif
                        </td>
                        <td>{{ $student->created_at->format('Y/m/d') }}</td>
                        <td>
                            @if($student->status === 'active' || is_null($student->status))
                                <span class="status-badge active">
                                    <i class="fas fa-check-circle"></i> نشط
                                </span>
                            @elseif($student->status === 'inactive')
                                <span class="status-badge inactive">
                                    <i class="fas fa-times-circle"></i> غير نشط
                                </span>
                            @else
                                <span class="status-badge blocked">
                                    <i class="fas fa-ban"></i> محظور
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="actions-dropdown">
                                <button class="actions-btn" onclick="toggleActions({{ $student->id }})">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div id="actions-{{ $student->id }}" class="actions-menu">
                                    <a href="{{ route('admin.students.show', $student->id) }}" class="action-item view">
                                        <i class="fas fa-eye"></i> عرض
                                    </a>
                                    <a href="{{ route('admin.students.edit', $student->id) }}" class="action-item edit">
                                        <i class="fas fa-edit"></i> تعديل
                                    </a>
                                    <button onclick="toggleStatus({{ $student->id }})" class="action-item toggle-status">
                                        <i class="fas fa-user-{{ $student->status === 'active' ? 'slash' : 'check' }}"></i>
                                        {{ $student->status === 'active' ? 'تعطيل' : 'تفعيل' }}
                                    </button>
                                    <button onclick="blockStudent({{ $student->id }})" class="action-item block">
                                        <i class="fas fa-ban"></i> حظر
                                    </button>
                                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-item delete" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم نهائياً؟')">
                                            <i class="fas fa-trash"></i> حذف
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="no-data">
                            <i class="fas fa-inbox"></i>
                            <p>لا يوجد طلاب مسجلين حالياً</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($students->hasPages())
        <div class="pagination">
            {{ $students->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal عرض الأنشطة -->
<div id="activitiesModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-calendar-alt"></i> أنشطة الطالب</h3>
            <button class="close-modal" onclick="closeActivitiesModal()">&times;</button>
        </div>
        <div class="modal-body" id="modalBody">
            <div class="loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>جاري تحميل البيانات...</p>
            </div>
        </div>
    </div>
</div>

<style>
    .students-management {
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

    .stat-icon.purple {
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
    }

    .stat-icon.green {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .stat-icon.blue {
        background: linear-gradient(135deg, #3b82f6, #1e40af);
    }

    .stat-info h3 {
        font-size: 18px;
        color: #374151;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .stat-number {
        font-size: 36px;
        font-weight: 900;
        color: #1e3a8a;
        margin: 0;
        line-height: 1;
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

    /* أزرار التصدير */
    .export-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-export {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-export.excel {
        background: #10b981;
        color: white;
    }

    .btn-export.excel:hover {
        background: #059669;
    }

    .btn-export.pdf {
        background: #ef4444;
        color: white;
    }

    .btn-export.pdf:hover {
        background: #dc2626;
    }

    /* الفلاتر المتقدمة */
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

    .btn-reset {
        padding: 10px 20px;
        background: #6b7280;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
        transition: background 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-reset:hover {
        background: #4b5563;
    }

    /* الجدول */
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

    .role-select {
        padding: 8px 12px;
        border: 2px solid #e5e7eb;
        border-radius: 6px;
        background: white;
        cursor: pointer;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        transition: border-color 0.3s, background 0.3s;
        min-width: 120px;
    }

    .role-select:focus {
        outline: none;
        border-color: #4f46e5;
    }

    .activities-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: #dbeafe;
        color: #1e40af;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .activities-badge.clickable {
        cursor: pointer;
        transition: all 0.3s;
    }

    .activities-badge.clickable:hover {
        background: #3b82f6;
        color: white;
        transform: translateY(-2px);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .status-badge.active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-badge.inactive {
        background: #fef3c7;
        color: #92400e;
    }

    .status-badge.blocked {
        background: #fee2e2;
        color: #991b1b;
    }

    /* قائمة الإجراءات */
    .actions-dropdown {
        position: relative;
    }

    .actions-btn {
        width: 36px;
        height: 36px;
        border: none;
        background: #f3f4f6;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .actions-btn:hover {
        background: #e5e7eb;
    }

    .actions-menu {
        position: absolute;
        left: 0;
        top: 100%;
        margin-top: 8px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        min-width: 180px;
        z-index: 100;
        display: none;
        overflow: hidden;
    }

    .actions-menu.show {
        display: block;
    }

    .action-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 15px;
        width: 100%;
        border: none;
        background: none;
        cursor: pointer;
        font-family: inherit;
        font-size: 14px;
        transition: all 0.3s;
        text-align: right;
    }

    .action-item:hover {
        background: #f9fafb;
    }

    .action-item.view {
        color: #3b82f6;
    }

    .action-item.edit {
        color: #10b981;
    }

    .action-item.toggle-status {
        color: #f59e0b;
    }

    .action-item.block {
        color: #ef4444;
    }

    .action-item.delete {
        color: #dc2626;
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

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal.show {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        width: 90%;
        max-width: 600px;
        max-height: 80vh;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }

    .modal-header {
        padding: 20px;
        border-bottom: 2px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        color: #1e3a8a;
        font-size: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .close-modal {
        width: 36px;
        height: 36px;
        border: none;
        background: #f3f4f6;
        border-radius: 50%;
        cursor: pointer;
        font-size: 24px;
        color: #6b7280;
        transition: all 0.3s;
    }

    .close-modal:hover {
        background: #e5e7eb;
        color: #111827;
    }

    .modal-body {
        padding: 20px;
        overflow-y: auto;
        max-height: 60vh;
    }

    .loading {
        text-align: center;
        padding: 40px;
        color: #6b7280;
    }

    .loading i {
        font-size: 48px;
        color: #4f46e5;
        margin-bottom: 15px;
    }

    .activity-item {
        padding: 15px;
        background: #f9fafb;
        border-radius: 8px;
        margin-bottom: 10px;
        border-right: 4px solid #4f46e5;
    }

    .activity-item h4 {
        margin: 0 0 8px 0;
        color: #1e3a8a;
        font-size: 16px;
    }

    .activity-item p {
        margin: 4px 0;
        color: #6b7280;
        font-size: 14px;
    }

    .activity-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 8px;
    }

    .activity-status.registered {
        background: #dbeafe;
        color: #1e40af;
    }

    .activity-status.completed {
        background: #d1fae5;
        color: #065f46;
    }
</style>

@push('scripts')
<script>
    // Toggle Actions Menu
    function toggleActions(studentId) {
        const menu = document.getElementById(`actions-${studentId}`);
        // Close all other menus
        document.querySelectorAll('.actions-menu').forEach(m => {
            if (m.id !== `actions-${studentId}`) {
                m.classList.remove('show');
            }
        });
        menu.classList.toggle('show');
    }

    // Close menus when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.actions-dropdown')) {
            document.querySelectorAll('.actions-menu').forEach(m => {
                m.classList.remove('show');
            });
        }
    });

    // Toggle Status
    function toggleStatus(studentId) {
        if (confirm('هل أنت متأكد من تغيير حالة هذا الطالب؟')) {
            fetch(`/admin/students/${studentId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => {
                if (response.ok) {
                    location.reload();
                }
            });
        }
    }

    // Block Student
    function blockStudent(studentId) {
        if (confirm('هل أنت متأكد من حظر هذا الطالب؟')) {
            fetch(`/admin/students/${studentId}/block`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => {
                if (response.ok) {
                    location.reload();
                }
            });
        }
    }

    // Open Activities Modal
    function openActivitiesModal(studentId, studentName) {
        const modal = document.getElementById('activitiesModal');
        const modalBody = document.getElementById('modalBody');
        
        modal.classList.add('show');
        modalBody.innerHTML = `
            <div class="loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>جاري تحميل بيانات أنشطة ${studentName}...</p>
            </div>
        `;

        fetch(`/admin/students/${studentId}/activities`)
            .then(response => response.json())
            .then(data => {
                if (data.activities && data.activities.length > 0) {
                    let html = `<h4 style="margin-bottom: 15px; color: #374151;">إجمالي ${data.activities.length} نشاط</h4>`;
                    data.activities.forEach(activity => {
                        html += `
                            <div class="activity-item">
                                <h4><i class="fas fa-calendar-alt"></i> ${activity.title}</h4>
                                <p><i class="fas fa-map-marker-alt"></i> ${activity.location || 'غير محدد'}</p>
                                <p><i class="fas fa-calendar"></i> ${activity.date || 'غير محدد'}</p>
                                <span class="activity-status ${activity.status === 'completed' ? 'completed' : 'registered'}">
                                    ${activity.status === 'completed' ? '✓ مكتمل' : '📝 مسجل'}
                                </span>
                            </div>
                        `;
                    });
                    modalBody.innerHTML = html;
                } else {
                    modalBody.innerHTML = `
                        <div style="text-align: center; padding: 40px; color: #9ca3af;">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px;"></i>
                            <p>لا توجد أنشطة مسجلة</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                modalBody.innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #ef4444;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 15px;"></i>
                        <p>حدث خطأ في تحميل البيانات</p>
                    </div>
                `;
            });
    }

    // Close Activities Modal
    function closeActivitiesModal() {
        document.getElementById('activitiesModal').classList.remove('show');
    }

    // Close modal on outside click
    document.getElementById('activitiesModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeActivitiesModal();
        }
    });

    // Export to Excel
    function exportToExcel() {
        window.location.href = '{{ route("admin.students.export", ["format" => "excel"]) }}?' + new URLSearchParams(new FormData(document.getElementById('filterForm')));
    }

    // Export to PDF
    function exportToPDF() {
        window.location.href = '{{ route("admin.students.export", ["format" => "pdf"]) }}?' + new URLSearchParams(new FormData(document.getElementById('filterForm')));
    }
</script>
@endpush
@endsection