@extends('layouts.admin')

@section('content')
<div class="admin-dashboard">
    <div class="dashboard-header">
        <h1>مرحباً بك، المشرف العام</h1>
        <p>لوحة تحكم إدارة الأنشطة الطلابية</p>
    </div>

    <!-- بطاقات الإحصائيات -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-info">
                <h3>مجموع الأنشطة</h3>
                <p class="stat-number">{{ $totalActivities ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>عدد الطلاب</h3>
                <p class="stat-number">{{ $totalStudents ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-info">
                <h3>إجمالي التسجيلات</h3>
                <p class="stat-number">{{ $totalRegistrations ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- الإجراءات السريعة -->
    <div class="quick-actions">
        <h2>إجراءات سريعة</h2>
        <div class="actions-grid">
            <a href="{{ route('activities.create') }}" class="action-btn">
                <i class="fas fa-plus-circle"></i>
                <span>إضافة نشاط جديد</span>
            </a>
            <a href="{{ route('admin.students') }}" class="action-btn">
                <i class="fas fa-user-plus"></i>
                <span>إضافة طالب جديد</span>
            </a>
            <a href="{{ route('admin.all-registrations') }}" class="action-btn">
                <i class="fas fa-list-alt"></i>
                <span>إدارة فعاليات القائمة</span>
            </a>
            <a href="#" class="action-btn">
                <i class="fas fa-file-export"></i>
                <span>إصدار تقرير فعلي</span>
            </a>
        </div>
    </div>
</div>

<style>
    .admin-dashboard {
        padding: 20px;
    }

    .dashboard-header {
        margin-bottom: 30px;
    }

    .dashboard-header h1 {
        color: #1e3a8a;
        font-size: 28px;
        margin-bottom: 5px;
    }

    .dashboard-header p {
        color: #6b7280;
        font-size: 16px;
    }

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

    .stat-card:nth-child(1) .stat-icon {
        background: linear-gradient(135deg, #3b82f6, #1e40af);
    }

    .stat-card:nth-child(2) .stat-icon {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .stat-card:nth-child(3) .stat-icon {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .stat-info h3 {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 5px;
    }

    .stat-number {
        font-size: 28px;
        font-weight: bold;
        color: #1e3a8a;
        margin: 0;
    }

    .quick-actions {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .quick-actions h2 {
        color: #1e3a8a;
        margin-bottom: 20px;
        font-size: 20px;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .action-btn {
        display: flex;
        align-items: center;
        padding: 15px;
        background: #f3f4f6;
        border-radius: 8px;
        text-decoration: none;
        color: #1e3a8a;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        background: #1e3a8a;
        color: white;
        transform: translateY(-2px);
    }

    .action-btn i {
        font-size: 20px;
        margin-left: 10px;
    }
</style>
@endsection