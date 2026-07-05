@extends('layouts.admin')

@section('content')

<style>
    /* ===== الأنماط العامة ===== */
    .activities-container {
        width: 100%;
        padding: 30px;
        max-width: 100%;
        direction: rtl;
    }

    /* ===== الهيدر ===== */
    .activities-header {
        background: linear-gradient(135deg, #7c3aed, #4f46e5);
        border-radius: 20px;
        padding: 30px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 8px 25px rgba(124, 58, 237, 0.3);
    }

    .activities-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .activities-header h1 {
        font-size: 32px;
        font-weight: bold;
        margin: 0 0 10px 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .activities-header h1 i {
        background: rgba(255,255,255,0.2);
        padding: 12px;
        border-radius: 12px;
    }

    .activities-header p {
        margin: 0;
        opacity: 0.95;
        font-size: 16px;
    }

    .btn-add-activity {
        background: white;
        color: #7c3aed;
        padding: 15px 30px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        border: none;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-add-activity:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    }

    /* ===== بطاقات الإحصائيات ===== */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-box {
        background: white;
        border-radius: 16px;
        padding: 25px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .stat-box::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 5px;
        height: 100%;
    }

    .stat-box.purple::before { background: linear-gradient(135deg, #a855f7, #7c3aed); }
    .stat-box.green::before { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-box.blue::before { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .stat-box.orange::before { background: linear-gradient(135deg, #f97316, #ea580c); }

    .stat-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .stat-icon {
        width: 65px;
        height: 65px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        flex-shrink: 0;
    }

    .stat-box.purple .stat-icon {
        background: linear-gradient(135deg, #f3e8ff, #e9d5ff);
        color: #a855f7;
    }

    .stat-box.green .stat-icon {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #059669;
    }

    .stat-box.blue .stat-icon {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #2563eb;
    }

    .stat-box.orange .stat-icon {
        background: linear-gradient(135deg, #ffedd5, #fed7aa);
        color: #ea580c;
    }

    .stat-details h3 {
        margin: 0 0 5px 0;
        font-size: 14px;
        color: #6b7280;
        font-weight: 600;
    }

    .stat-details p {
        margin: 0;
        font-size: 32px;
        font-weight: bold;
        color: #1f2937;
    }

    /* ===== شريط البحث والفلترة ===== */
    .search-filter-bar {
        background: white;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .filter-group label {
        font-weight: 600;
        color: #374151;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-group input,
    .filter-group select {
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s;
        font-family: inherit;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }

    .search-input-wrapper {
        position: relative;
    }

    .search-input-wrapper input {
        padding-right: 45px;
    }

    .search-input-wrapper i {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    /* ===== شبكة الأنشطة ===== */
    .activities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .activity-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
        border: 1px solid #e5e7eb;
    }

    .activity-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        border-color: #7c3aed;
    }

    .activity-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, #7c3aed, #4f46e5);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 64px;
        color: white;
        position: relative;
    }

    .activity-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .activity-content {
        padding: 25px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .activity-category {
        display: inline-block;
        background: linear-gradient(135deg, #f3e8ff, #e9d5ff);
        color: #7c3aed;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 12px;
        align-self: flex-start;
        border: 1px solid #c4b5fd;
    }

    .activity-title {
        font-size: 20px;
        font-weight: bold;
        color: #1f2937;
        margin: 0 0 10px 0;
        line-height: 1.4;
    }

    .activity-description {
        color: #6b7280;
        font-size: 14px;
        line-height: 1.6;
        margin: 0 0 20px 0;
        flex: 1;
    }

    .activity-meta {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 20px;
        padding: 15px;
        background: #f9fafb;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #4b5563;
        font-size: 14px;
    }

    .meta-item i {
        color: #7c3aed;
        width: 20px;
    }

    .activity-rating {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
    }

    .rating-stars {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #d97706;
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
        border: 1px solid #fcd34d;
    }

    .activity-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .btn-action {
        padding: 12px 20px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 14px;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #7c3aed, #4f46e5);
        color: white;
        box-shadow: 0 4px 10px rgba(124, 58, 237, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(124, 58, 237, 0.4);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.4);
    }

    .btn-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3);
    }

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(245, 158, 11, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(239, 68, 68, 0.4);
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 2px dashed #e5e7eb;
    }

    .empty-state i {
        font-size: 80px;
        color: #d1d5db;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        color: #4b5563;
        font-size: 24px;
        margin: 0 0 10px 0;
    }

    .empty-state p {
        color: #9ca3af;
        font-size: 16px;
        margin: 0 0 25px 0;
    }

    /* ===== التجاوب ===== */
    @media (max-width: 768px) {
        .activities-container {
            padding: 15px;
        }

        .activities-header {
            padding: 20px;
        }

        .activities-header h1 {
            font-size: 24px;
        }

        .activities-grid {
            grid-template-columns: 1fr;
        }

        .stats-row {
            grid-template-columns: 1fr;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="activities-container">
    
    <!-- Hero Section -->
    <div class="activities-header">
        <div class="activities-header-content">
            <div>
                <h1>
                    <i class="fas fa-calendar-alt"></i>
                    إدارة الأنشطة الطلابية
                </h1>
                <p>استكشف جميع الأنشطة والفعاليات المتاحة للتسجيل</p>
            </div>
            @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('activities.create') }}" class="btn-add-activity">
                <i class="fas fa-plus"></i>
                إضافة نشاط
            </a>
            @endif
        </div>
    </div>

    <!-- Stats Section -->
    <div class="stats-row">
        <div class="stat-box purple">
            <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-details">
                <h3>إجمالي الأنشطة</h3>
                <p>{{ $activities->count() ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-box green">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-details">
                <h3>الأنشطة المتاحة</h3>
                <p>{{ $activities->where('status', 'active')->count() ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-box blue">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-details">
                <h3>تسجيلاتي</h3>
                <p>{{ auth()->user()->activities()->count() ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-box orange">
            <div class="stat-icon">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-details">
                <h3>التقييم العام</h3>
                <p>4.8</p>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="search-filter-bar">
        <form method="GET" action="{{ route('activities.index') }}">
            <div class="filter-grid">
                <div class="filter-group">
                    <label><i class="fas fa-search"></i> البحث</label>
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث عن نشاط...">
                    </div>
                </div>

                <div class="filter-group">
                    <label><i class="fas fa-filter"></i> الفئة</label>
                    <select name="category">
                        <option value="">جميع الفئات</option>
                        <option value="academic" {{ request('category') == 'academic' ? 'selected' : '' }}>الأنشطة التقنية والأكاديمية</option>
                        <option value="cultural" {{ request('category') == 'cultural' ? 'selected' : '' }}>الأنشطة الثقافية</option>
                        <option value="sports" {{ request('category') == 'sports' ? 'selected' : '' }}>الأنشطة الرياضية</option>
                        <option value="social" {{ request('category') == 'social' ? 'selected' : '' }}>الأنشطة الاجتماعية</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label><i class="fas fa-calendar"></i> الفترة</label>
                    <select name="period">
                        <option value="">جميع الفترات</option>
                        <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>اليوم</option>
                        <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>هذا الأسبوع</option>
                        <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>هذا الشهر</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-action btn-primary">
                        <i class="fas fa-search"></i>
                        بحث
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Activities Grid -->
    @if(isset($activities) && $activities->count() > 0)
    <div class="activities-grid">
        @foreach($activities as $activity)
        <div class="activity-card">
            <div class="activity-image">
                @if($activity->image)
                    <img src="{{ $activity->image }}" alt="{{ $activity->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <i class="fas fa-calendar-alt"></i>
                @endif
                
                @if($activity->status == 'active')
                <span class="activity-badge">
                    <i class="fas fa-check-circle"></i>
                    متاح
                </span>
                @endif
            </div>

            <div class="activity-content">
                <span class="activity-category">
                    <i class="fas fa-tag"></i>
                    {{ $activity->category ?? 'الأنشطة التقنية والأكاديمية' }}
                </span>

                <h3 class="activity-title">{{ $activity->title }}</h3>
                
                <p class="activity-description">
                    {{ Str::limit($activity->description ?? 'وصف النشاط', 100) }}
                </p>

                <div class="activity-meta">
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $activity->location ?? 'قاعة القدس' }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        <span>{{ $activity->date ? \Carbon\Carbon::parse($activity->date)->format('Y/m/d') : '2026/07/17' }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        <span>{{ $activity->registered_count ?? rand(2, 30) }} / {{ $activity->max_participants ?? 30 }} مسجل</span>
                    </div>
                </div>

                <div class="activity-rating">
                    <span class="rating-stars">
                        <i class="fas fa-star"></i>
                        {{ $activity->rating ?? '10' }}
                    </span>
                </div>

                <div class="activity-actions">
                    <a href="{{ route('activities.show', $activity->id) }}" class="btn-action btn-primary">
                        <i class="fas fa-eye"></i>
                        التفاصيل
                    </a>
                    
                    <button class="btn-action btn-success">
                        <i class="fas fa-users"></i>
                        عرض المسجلين ({{ $activity->registered_count ?? rand(2, 10) }})
                    </button>
                    
                    @if(auth()->user()->role == 'admin')
                    <a href="{{ route('activities.edit', $activity->id) }}" class="btn-action btn-warning">
                        <i class="fas fa-edit"></i>
                        تعديل
                    </a>
                    
                    <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا النشاط؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-danger" style="width: 100%;">
                            <i class="fas fa-trash"></i>
                            حذف
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-calendar-times"></i>
        <h3>لا توجد أنشطة متاحة</h3>
        <p>لا توجد أنشطة مطابقة لبحثك حالياً</p>
        <a href="{{ route('activities.index') }}" class="btn-action btn-primary">
            <i class="fas fa-redo"></i>
            إعادة تعيين الفلاتر
        </a>
    </div>
    @endif
</div>

@endsection