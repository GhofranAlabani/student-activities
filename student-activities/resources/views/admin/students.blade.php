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
                <p class="stat-number">{{ \App\Models\User::count() }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-info">
                <h3>الطلاب</h3>
                <p class="stat-number">{{ \App\Models\User::where('role', 'student')->count() }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="stat-info">
                <h3>المدراء</h3>
                <p class="stat-number">{{ \App\Models\User::where('role', 'admin')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- حاوية الجدول -->
    <div class="table-container">
        <div class="table-header">
            <h2 class="table-title">قائمة الطلاب</h2>
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="ابحث بالاسم أو البريد الإلكتروني...">
                <button><i class="fas fa-search"></i> بحث</button>
            </div>
        </div>

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
                        <th>تغيير الصلاحية</th>
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
                                <span>{{ $student->name }}</span>
                            </div>
                        </td>
                        <td>{{ $student->email }}</td>
                        <td>
                            <span class="badge {{ $student->role === 'admin' ? 'badge-admin' : 'badge-student' }}">
                                <i class="fas {{ $student->role === 'admin' ? 'fa-shield-alt' : 'fa-user-graduate' }}"></i>
                                {{ $student->role === 'admin' ? 'مدير' : 'طالب' }}
                            </span>
                        </td>
                        <td>
                            <span class="activities-count">
                                {{ $student->activities()->count() }} نشاط
                            </span>
                        </td>
                        <td>{{ $student->created_at->format('Y/m/d') }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.user.role', $student->id) }}" class="role-form">
                                @csrf
                                @method('PATCH')
                                <select name="role" class="role-select" onchange="this.form.submit()">
                                    <option value="student" {{ $student->role === 'student' ? 'selected' : '' }}>
                                        طالب
                                    </option>
                                    <option value="admin" {{ $student->role === 'admin' ? 'selected' : '' }}>
                                        مدير
                                    </option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="no-data">
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
            {{ $students->links() }}
        </div>
        @endif
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
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }

    .badge-student {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-admin {
        background: #fce7f3;
        color: #9d174d;
    }

    .activities-count {
        background: #f3f4f6;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 13px;
        color: #4b5563;
    }

    .role-select {
        padding: 8px 12px;
        border: 2px solid #e5e7eb;
        border-radius: 6px;
        background: white;
        cursor: pointer;
        font-family: inherit;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .role-select:focus {
        outline: none;
        border-color: #4f46e5;
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
</style>

@push('scripts')
<script>
    // البحث في الجدول
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const rows = document.querySelectorAll('#studentsTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchText) ? '' : 'none';
        });
    });

    // تأكيد تغيير الصلاحية
    document.querySelectorAll('.role-select').forEach(select => {
        select.addEventListener('change', function(e) {
            const newRole = this.value === 'admin' ? 'مدير' : 'طالب';
            if (!confirm(`هل أنت متأكد من تغيير صلاحية هذا المستخدم إلى "${newRole}"؟`)) {
                e.preventDefault();
                // إعادة الخيار السابق
                const originalRole = this.dataset.originalRole || 'student';
                this.value = originalRole;
                return;
            }
            this.dataset.originalRole = this.value;
        });
    });
</script>
@endpush
@endsection