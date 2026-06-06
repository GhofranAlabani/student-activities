@extends('layouts.admin')

@section('content')
<div class="announcements-management">
    <div class="page-header">
        <h1><i class="fas fa-bullhorn"></i> إدارة الإعلانات</h1>
        <a href="{{ route('admin.announcements.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> إضافة إعلان جديد
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-container">
        <table class="announcements-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>العنوان</th>
                    <th>النوع</th>
                    <th>النشاط المرتبط</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($announcements as $announcement)
                <tr>
                    <td>{{ $announcement->id }}</td>
                    <td>{{ Str::limit($announcement->title, 50) }}</td>
                    <td>
                        <span class="badge badge-{{ $announcement->type }}">
                            {{ $announcement->type === 'activity' ? 'نشاط' : ($announcement->type === 'warning' ? 'تحذير' : 'عام') }}
                        </span>
                    </td>
                    <td>{{ $announcement->activity->title ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $announcement->is_active ? 'badge-active' : 'badge-inactive' }}">
                            {{ $announcement->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td>{{ $announcement->created_at->format('Y/m/d') }}</td>
                    <td>
                        <a href="{{ route('admin.announcements.edit', $announcement->id) }}" class="btn-sm btn-edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.announcements.destroy', $announcement->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="no-data">لا توجد إعلانات</td>
                </tr>
                @endforelse
            </tbody>
        </table>
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
    .table-container {
        background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .announcements-table { width: 100%; border-collapse: collapse; }
    .announcements-table th, .announcements-table td { padding: 14px 12px; text-align: right; border-bottom: 1px solid #e5e7eb; }
    .announcements-table th { background: #f9fafb; font-weight: 600; }
    .badge { padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 500; }
    .badge-activity { background: #d1fae5; color: #065f46; }
    .badge-warning { background: #fee2e2; color: #991b1b; }
    .badge-general { background: #dbeafe; color: #1e40af; }
    .badge-active { background: #d1fae5; color: #065f46; }
    .badge-inactive { background: #fee2e2; color: #991b1b; }
    .btn-sm { padding: 6px 10px; border: none; border-radius: 6px; cursor: pointer; margin-left: 5px; text-decoration: none; display: inline-flex; }
    .btn-edit { background: #f59e0b; color: white; }
    .btn-delete { background: #ef4444; color: white; }
    .no-data { text-align: center; padding: 40px; color: #9ca3af; }
</style>
@endsection