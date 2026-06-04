@extends('layouts.admin')

@section('content')
<div class="staff-management">
    <div class="page-header">
        <h1><i class="fas fa-chalkboard-teacher"></i> إدارة الكوادر والمشرفين</h1>
        <a href="{{ route('admin.staff.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> إضافة مشرف جديد
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>إجمالي المشرفين</h3>
                <p class="stat-number">{{ \App\Models\Staff::count() }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-info">
                <h3>مشرفين نشطين</h3>
                <p class="stat-number">{{ \App\Models\Staff::where('status', 'active')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table class="staff-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>المنصب</th>
                    <th>القسم</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff as $member)
                <tr>
                    <td>{{ $member->id }}</td>
                    <td>{{ $member->user->name }}</td>
                    <td>{{ $member->user->email }}</td>
                    <td>{{ $member->position ?? '-' }}</td>
                    <td>{{ $member->department ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $member->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                            {{ $member->status === 'active' ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.staff.show', $member->id) }}" class="btn-sm btn-view" title="عرض">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.staff.edit', $member->id) }}" class="btn-sm btn-edit" title="تعديل">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.staff.destroy', $member->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المشرف؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-sm btn-delete" title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="no-data">لا يوجد مشرفين مسجلين</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $staff->links() }}
        </div>
    </div>
</div>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    .page-header h1 { color: #1e3a8a; font-size: 28px; }
    .btn-primary {
        background: #4f46e5; color: white; padding: 12px 24px;
        border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
    }
    .alert-success {
        background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px;
    }
    .stats-cards {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px; margin-bottom: 30px;
    }
    .stat-card {
        background: white; border-radius: 12px; padding: 20px;
        display: flex; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .stat-icon {
        width: 60px; height: 60px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        margin-left: 15px; font-size: 24px; color: white;
    }
    .stat-icon.blue { background: linear-gradient(135deg, #3b82f6, #1e40af); }
    .stat-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-info h3 { font-size: 14px; color: #6b7280; margin-bottom: 5px; }
    .stat-number { font-size: 28px; font-weight: bold; color: #1e3a8a; margin: 0; }
    .table-container {
        background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .staff-table { width: 100%; border-collapse: collapse; }
    .staff-table th, .staff-table td { padding: 14px 12px; text-align: right; border-bottom: 1px solid #e5e7eb; }
    .staff-table th { background: #f9fafb; font-weight: 600; color: #374151; }
    .badge { padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 500; }
    .badge-active { background: #d1fae5; color: #065f46; }
    .badge-inactive { background: #fee2e2; color: #991b1b; }
    .btn-sm { padding: 6px 10px; border: none; border-radius: 6px; cursor: pointer; margin-left: 5px; text-decoration: none; display: inline-flex; }
    .btn-view { background: #3b82f6; color: white; }
    .btn-edit { background: #f59e0b; color: white; }
    .btn-delete { background: #ef4444; color: white; }
    .no-data { text-align: center; padding: 40px; color: #9ca3af; }
    .pagination { margin-top: 20px; }
</style>
@endsection