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
                        
                        <!-- ✅ عمود الصلاحية مع زر التغيير -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <!-- Badge الصلاحية الحالية -->
                                <span class="role-badge {{ $student->role === 'admin' ? 'admin' : 'student' }}" id="role-badge-{{ $student->id }}">
                                    {{ $student->role === 'admin' ? '👨‍ مدير' : '🎓 طالب' }}
                                </span>
                                
                                <!-- زر تغيير الصلاحية (يظهر فقط للأدمن ولا يظهر للأدمن الحالي) -->
                                @if(auth()->user()->role === 'admin' && $student->id !== auth()->id())
                                    <button onclick="openRoleModal({{ $student->id }}, '{{ $student->name }}', '{{ $student->role }}')" 
                                            class="w-8 h-8 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-700 flex items-center justify-center transition" 
                                            title="تغيير الصلاحية">
                                        <i class="fas fa-exchange-alt text-sm"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                        
                        <td>{{ $student->created_at ? $student->created_at->format('Y/m/d') : 'غير محدد' }}</td>
                        
                        <!-- ✅ عمود الإجراءات -->
                        <td>
                            <div class="actions-buttons">
                                <button onclick="viewStudent({{ $student->id }}, @json($student))" class="action-btn view" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="openDeleteModal({{ $student->id }}, '{{ $student->name }}')" class="action-btn delete" title="حذف">
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

<!-- ============================================ -->
<!-- Modal عرض تفاصيل الطالب (تصميم احترافي) -->
<!-- ============================================ -->
<div id="viewModal" class="modal">
    <div class="modal-content-large">
        <div class="modal-header-blue">
            <div class="modal-header-content">
                <div class="modal-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h3 id="viewModalTitle">تفاصيل الطالب</h3>
                    <p class="modal-subtitle">معلومات الحساب والنشاط</p>
                </div>
            </div>
            <button class="close-modal" onclick="closeViewModal()">&times;</button>
        </div>
        
        <div class="modal-body" id="viewModalBody">
            <!-- سيتم ملء المحتوى بواسطة JavaScript -->
        </div>
        
        <div class="modal-footer">
            <button onclick="closeViewModal()" class="btn-modal-secondary">
                إغلاق
            </button>
            <button onclick="editCurrentStudent()" class="btn-modal-primary">
                <i class="fas fa-edit"></i> تعديل البيانات
            </button>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- Modal تأكيد الحذف (نفس تصميم صورتك) -->
<!-- ============================================ -->
<div id="deleteModal" class="modal">
    <div class="modal-content-small">
        <div class="delete-warning-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        
        <h2 class="delete-title">تأكيد حذف الحساب</h2>
        
        <p class="delete-message">
            هل أنت متأكد من حذف حساب <span id="deleteUserName" class="highlight-red"></span>؟<br>
            هذا الإجراء لا يمكن التراجع عنه.
        </p>
        
        <div class="password-field">
            <label class="password-label">أدخل كلمة المرور للتأكيد</label>
            <input type="password" id="deletePassword" placeholder="••••••••" class="password-input">
        </div>
        
        <div class="delete-actions">
            <button onclick="closeDeleteModal()" class="btn-cancel">إلغاء</button>
            <button onclick="confirmDelete()" class="btn-delete-confirm">
                نعم، احذف الحساب
            </button>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ✅ Modal تغيير الصلاحية (جديد) -->
<!-- ============================================ -->
<div id="roleModal" class="modal">
    <div class="modal-content-small">
        <!-- أيقونة -->
        <div class="delete-warning-icon" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
            <i class="fas fa-user-shield" style="color: #d97706;"></i>
        </div>
        
        <h2 class="delete-title">تغيير صلاحية المستخدم</h2>
        
        <p class="delete-message">
            هل تريد تغيير صلاحية <span id="roleUserName" class="highlight-red"></span>؟
        </p>
        
        <!-- خيارات الصلاحية -->
        <form id="roleForm" method="POST" style="text-align: right;">
            @csrf
            @method('PATCH')
            
            <div style="margin-bottom: 20px;">
                <label class="password-label">اختر الصلاحية الجديدة:</label>
                <select name="role" id="roleSelect" class="password-input" style="width: 100%; margin-top: 8px;">
                    <option value="student">🎓 طالب</option>
                    <option value="admin">👨‍💼 مدير نظام</option>
                </select>
            </div>
            
            <div class="delete-actions">
                <button type="button" onclick="closeRoleModal()" class="btn-cancel">إلغاء</button>
                <button type="submit" class="btn-delete-confirm" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    تأكيد التغيير
                </button>
            </div>
        </form>
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

    /* ============================================ */
    /* Modal Styles - التصاميم الاحترافية */
    /* ============================================ */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }

    .modal.show {
        display: flex;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content-large {
        background: white;
        border-radius: 24px;
        width: 90%;
        max-width: 700px;
        max-height: 85vh;
        overflow: hidden;
        box-shadow: 0 25px 80px rgba(0,0,0,0.4);
        animation: slideUp 0.4s ease;
    }

    .modal-content-small {
        background: white;
        border-radius: 24px;
        width: 90%;
        max-width: 450px;
        padding: 40px;
        text-align: center;
        box-shadow: 0 25px 80px rgba(0,0,0,0.4);
        animation: slideUp 0.4s ease;
    }

    @keyframes slideUp {
        from { 
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        to { 
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Modal Header - أزرق متدرج */
    .modal-header-blue {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        padding: 30px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header-content {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .modal-avatar {
        width: 64px;
        height: 64px;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }

    .modal-subtitle {
        margin: 4px 0 0 0;
        font-size: 14px;
        opacity: 0.9;
    }

    .close-modal {
        width: 40px;
        height: 40px;
        border: none;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        cursor: pointer;
        font-size: 24px;
        color: white;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .close-modal:hover {
        background: rgba(255,255,255,0.3);
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 30px;
        overflow-y: auto;
        max-height: 55vh;
    }

    .modal-footer {
        padding: 20px 30px;
        border-top: 2px solid #e5e7eb;
        background: #f9fafb;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    /* أزرار Modal */
    .btn-modal-primary,
    .btn-modal-secondary {
        padding: 12px 24px;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-modal-primary {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-modal-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
    }

    .btn-modal-secondary {
        background: white;
        border: 2px solid #d1d5db;
        color: #374151;
    }

    .btn-modal-secondary:hover {
        background: #f3f4f6;
        border-color: #9ca3af;
    }

    /* Delete Modal Styles */
    .delete-warning-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px auto;
    }

    .delete-warning-icon i {
        font-size: 40px;
        color: #dc2626;
    }

    .delete-title {
        font-size: 24px;
        font-weight: 800;
        color: #1e293b;
        margin: 0 0 16px 0;
    }

    .delete-message {
        font-size: 15px;
        color: #64748b;
        line-height: 1.8;
        margin-bottom: 24px;
    }

    .highlight-red {
        color: #dc2626;
        font-weight: 700;
    }

    .password-field {
        margin-bottom: 24px;
        text-align: right;
    }

    .password-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
    }

    .password-input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s;
        box-sizing: border-box;
    }

    .password-input:focus {
        outline: none;
        border-color: #dc2626;
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
    }

    .delete-actions {
        display: flex;
        gap: 12px;
    }

    .btn-cancel,
    .btn-delete-confirm {
        flex: 1;
        padding: 14px 20px;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 14px;
    }

    .btn-cancel {
        background: #f1f5f9;
        color: #475569;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
    }

    .btn-delete-confirm {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .btn-delete-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
    }

    /* Student Details in Modal */
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .detail-card {
        background: #f8fafc;
        padding: 16px;
        border-radius: 12px;
        border-right: 4px solid #3b82f6;
    }

    .detail-card.full-width {
        grid-column: 1 / -1;
    }

    .detail-label {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 6px;
        font-weight: 600;
    }

    .detail-value {
        font-size: 15px;
        color: #1e293b;
        font-weight: 700;
    }

    .activities-list {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        padding: 20px;
        border-radius: 12px;
        border: 2px solid #fcd34d;
    }

    .activities-list h4 {
        margin: 0 0 12px 0;
        color: #92400e;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .activity-item {
        padding: 10px;
        background: white;
        border-radius: 8px;
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .activity-name {
        font-weight: 600;
        color: #1e293b;
    }

    .activity-date {
        font-size: 12px;
        color: #64748b;
        background: #f1f5f9;
        padding: 4px 8px;
        border-radius: 6px;
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
        .detail-grid {
            grid-template-columns: 1fr;
        }
        .modal-content-small {
            padding: 30px 20px;
        }
    }
</style>

<script>
let currentStudentId = null;
let currentStudentData = null;

// عرض تفاصيل الطالب
function viewStudent(studentId, studentData) {
    currentStudentId = studentId;
    currentStudentData = studentData;
    
    const modal = document.getElementById('viewModal');
    const modalBody = document.getElementById('viewModalBody');
    const modalTitle = document.getElementById('viewModalTitle');
    
    modalTitle.textContent = studentData.name;
    
    modalBody.innerHTML = `
        <div class="detail-grid">
            <div class="detail-card">
                <div class="detail-label"><i class="fas fa-user"></i> الاسم الكامل</div>
                <div class="detail-value">${studentData.name}</div>
            </div>
            
            <div class="detail-card">
                <div class="detail-label"><i class="fas fa-envelope"></i> البريد الإلكتروني</div>
                <div class="detail-value">${studentData.email}</div>
            </div>
            
            <div class="detail-card">
                <div class="detail-label"><i class="fas fa-user-tag"></i> الصلاحية</div>
                <div class="detail-value">
                    <span class="role-badge ${studentData.role === 'admin' ? 'admin' : 'student'}">
                        ${studentData.role === 'admin' ? '👨‍💼 مدير' : '🎓 طالب'}
                    </span>
                </div>
            </div>
            
            <div class="detail-card">
                <div class="detail-label"><i class="fas fa-calendar"></i> تاريخ التسجيل</div>
                <div class="detail-value">${studentData.created_at ? new Date(studentData.created_at).toLocaleDateString('ar-SA') : 'غير محدد'}</div>
            </div>
            
            ${studentData.phone ? `
            <div class="detail-card">
                <div class="detail-label"><i class="fas fa-phone"></i> رقم الهاتف</div>
                <div class="detail-value">${studentData.phone}</div>
            </div>
            ` : ''}
            
            <div class="detail-card full-width activities-list">
                <h4><i class="fas fa-chart-bar"></i> معلومات إضافية</h4>
                <p style="margin: 0; color: #92400e;">
                    عدد الأنشطة المسجلة: <strong>0</strong> نشاط
                </p>
            </div>
        </div>
    `;
    
    modal.classList.add('show');
}

// فتح Modal الحذف
function openDeleteModal(studentId, studentName) {
    currentStudentId = studentId;
    document.getElementById('deleteUserName').textContent = studentName;
    document.getElementById('deletePassword').value = '';
    document.getElementById('deleteModal').classList.add('show');
}

// ✅ فتح Modal تغيير الصلاحية
function openRoleModal(userId, userName, currentRole) {
    currentStudentId = userId;
    document.getElementById('roleUserName').textContent = userName;
    document.getElementById('roleSelect').value = currentRole;
    
    // تحديث رابط الـ Form
    document.getElementById('roleForm').action = `/admin/users/${userId}/role`;
    
    document.getElementById('roleModal').classList.add('show');
}

// ✅ إغلاق Modal الصلاحية
function closeRoleModal() {
    document.getElementById('roleModal').classList.remove('show');
}

// إغلاق Modal العرض
function closeViewModal() {
    document.getElementById('viewModal').classList.remove('show');
}

// إغلاق Modal الحذف
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('show');
}

// تعديل الطالب الحالي
function editCurrentStudent() {
    if (currentStudentId) {
        window.location.href = `/admin/students/${currentStudentId}/edit`;
    }
}

// تأكيد الحذف
function confirmDelete() {
    const password = document.getElementById('deletePassword').value;
    
    if (!password) {
        alert('الرجاء إدخال كلمة المرور للتأكيد');
        return;
    }
    
    // إرسال طلب الحذف
    fetch(`/admin/students/${currentStudentId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Password': password
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'كلمة المرور غير صحيحة!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء الحذف');
    });
}

// ✅ معالجة إرسال فورم تغيير الصلاحية
document.getElementById('roleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // حالة التحميل
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التغيير...';
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            _method: 'PATCH',
            role: document.getElementById('roleSelect').value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success || data.message) {
            // تحديث الواجهة دون إعادة تحميل الصفحة
            const badge = document.getElementById(`role-badge-${currentStudentId}`);
            const newRole = document.getElementById('roleSelect').value;
            
            if (newRole === 'admin') {
                badge.className = 'role-badge admin';
                badge.innerHTML = '👨💼 مدير';
            } else {
                badge.className = 'role-badge student';
                badge.innerHTML = '🎓 طالب';
            }
            
            closeRoleModal();
            // عرض رسالة نجاح بسيطة
            alert('✅ ' + (data.message || 'تم تحديث الصلاحية بنجاح'));
        } else {
            alert('❌ حدث خطأ: ' + (data.message || 'غير معروف'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ فشل الاتصال بالخادم');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// إغلاق الـ Modals عند النقر خارجها
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeViewModal();
            closeDeleteModal();
            closeRoleModal();
        }
    });
});

// إغلاق الـ Modals بزر Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeViewModal();
        closeDeleteModal();
        closeRoleModal();
    }
});
</script>
@endsection