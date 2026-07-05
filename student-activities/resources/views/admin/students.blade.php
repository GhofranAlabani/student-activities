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
                <p class="stat-number">{{ $totalUsers ?? $students->count() ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-user-graduate"></i>
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
        </div>

        <!-- الفلاتر المتقدمة -->
        <div class="advanced-filters">
            <form method="GET" action="{{ url()->current() }}" id="filterForm">
                <div class="filters-grid">
                    <!-- حقل البحث -->
                    <div class="filter-group">
                        <label><i class="fas fa-search"></i> البحث</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث بالاسم أو البريد...">
                    </div>

                    <!-- فلتر الصلاحية -->
                    <div class="filter-group">
                        <label><i class="fas fa-user-tag"></i> الصلاحية</label>
                        <select name="role">
                            <option value="">الكل</option>
                            <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>طلاب فقط</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>مدراء فقط</option>
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
                        <th>المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الصلاحية</th>
                        <th>تاريخ التسجيل</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students ?? [] as $index => $student)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="user-info">
                                <div class="avatar" style="background: {{ ($student->role ?? 'student') === 'admin' ? '#dc2626' : '#4f46e5' }}">
                                    {{ substr($student->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <div class="user-name">{{ $student->name ?? 'غير محدد' }}</div>
                                    @if($student->phone ?? false)
                                        <div class="user-phone"><i class="fas fa-phone"></i> {{ $student->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $student->email ?? 'غير متوفر' }}</td>
                        <td>
                            <span class="role-badge {{ ($student->role ?? 'student') === 'admin' ? 'admin' : 'student' }}">
                                @if(($student->role ?? 'student') === 'admin')
                                    👨‍💼 مدير
                                @else
                                    👨‍🎓 طالب
                                @endif
                            </span>
                        </td>
                        <td>{{ $student->created_at ? $student->created_at->format('Y/m/d') : 'غير محدد' }}</td>
                        <td>
                            <div class="actions-buttons">
                                <button onclick="viewStudent({{ $student->id }})" class="action-btn view" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="deleteStudent({{ $student->id }})" class="action-btn delete" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="no-data">
                            <i class="fas fa-inbox"></i>
                            <p>لا يوجد طلاب مسجلين حالياً</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal عرض الطالب -->
<div id="studentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-user"></i> تفاصيل الطالب</h3>
            <button class="close-modal" onclick="closeStudentModal()">&times;</button>
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
        direction: rtl;
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

    .role-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .role-badge.admin {
        background: #fee2e2;
        color: #991b1b;
    }

    .role-badge.student {
        background: #dbeafe;
        color: #1e40af;
    }

    /* أزرار الإجراءات */
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
    }

    .action-btn.view {
        background: #dbeafe;
        color: #2563eb;
    }

    .action-btn.view:hover {
        background: #2563eb;
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

    .student-detail {
        padding: 15px;
        background: #f9fafb;
        border-radius: 8px;
        margin-bottom: 10px;
        border-right: 4px solid #4f46e5;
    }

    .student-detail h4 {
        margin: 0 0 8px 0;
        color: #1e3a8a;
        font-size: 16px;
    }

    .student-detail p {
        margin: 4px 0;
        color: #6b7280;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .students-management {
            padding: 10px;
        }
        .stats-cards {
            grid-template-columns: 1fr;
        }
        .filters-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    // عرض تفاصيل الطالب
    function viewStudent(studentId) {
        const modal = document.getElementById('studentModal');
        const modalBody = document.getElementById('modalBody');
        
        modal.classList.add('show');
        modalBody.innerHTML = `
            <div class="loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>جاري تحميل البيانات...</p>
            </div>
        `;

        // محاكاة تحميل البيانات
        setTimeout(() => {
            modalBody.innerHTML = `
                <div class="student-detail">
                    <h4><i class="fas fa-user"></i> معلومات الطالب</h4>
                    <p><strong>رقم الطالب:</strong> ${studentId}</p>
                    <p><strong>ملاحظة:</strong> هذه الصفحة تحتاج إلى تعديل الـ Controller لعرض التفاصيل الكاملة</p>
                </div>
            `;
        }, 500);
    }

    // حذف الطالب
    function deleteStudent(studentId) {
        if (confirm('هل أنت متأكد من حذف هذا الطالب؟')) {
            alert('الوظيفة تحتاج إلى إعداد الـ Controller والـ Routes');
        }
    }

    // إغلاق Modal
    function closeStudentModal() {
        document.getElementById('studentModal').classList.remove('show');
    }

    // إغلاق modal عند النقر خارجه
    document.getElementById('studentModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeStudentModal();
        }
    });
</script>
@endsection