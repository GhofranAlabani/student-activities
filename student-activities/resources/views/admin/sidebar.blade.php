<aside class="sidebar" style="background: #0a1929 !important;">
<style>
    /* إجبار كامل على الألوان */
    aside.sidebar {
        background-color: #0a1929 !important;
    }
    aside.sidebar .sidebar-header {
        background: transparent !important;
        border-bottom: 1px solid rgba(255,255,255,0.1) !important;
    }
    aside.sidebar .sidebar-header h3 {
        color: #d4a017 !important;
    }
    aside.sidebar .sidebar-nav a {
        color: #d1d5db !important;
        background: transparent !important;
    }
    aside.sidebar .sidebar-nav a i {
        color: #d4a017 !important;
    }
    aside.sidebar .sidebar-nav a:hover,
    aside.sidebar .sidebar-nav a.active {
        background: #d4a017 !important;
        color: #0a1929 !important;
    }
    aside.sidebar .sidebar-nav a:hover i,
    aside.sidebar .sidebar-nav a.active i {
        color: #0a1929 !important;
    }
    aside.sidebar .section-title {
        color: rgba(255,255,255,0.4) !important;
    }
    aside.sidebar .logout-btn {
        background: #ef4444 !important;
        color: white !important;
    }
    aside.sidebar .logout-btn:hover {
        background: #dc2626 !important;
    }
</style>

    <!-- الهيدر -->
    <div class="sidebar-header" style="padding: 25px 20px; text-align: center;">
        <div style="display: inline-flex; align-items: center; justify-content: center; width: 70px; height: 70px; background: #d4a017; border-radius: 50%; margin-bottom: 12px;">
            <i class="fas fa-user-shield" style="font-size: 32px; color: #0a1929;"></i>
        </div>
        <h3 style="margin: 0; font-size: 1.2rem; font-weight: 700;">لوحة الإدارة</h3>
    </div>
    
    <!-- القائمة -->
    <nav class="sidebar-nav" style="flex: 1; padding: 20px 15px;">
        <ul style="list-style: none; padding: 0; margin: 0;">
            
            <li style="margin-bottom: 5px;">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; text-decoration: none;">
                    <i class="fas fa-home" style="font-size: 1.1rem; width: 22px; text-align: center;"></i>
                    <span>الرئيسية</span>
                </a>
            </li>
            
            <li style="padding: 15px 16px 8px; font-size: 0.7rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase;" class="section-title">
                الإدارة
            </li>
            
            <li style="margin-bottom: 5px;">
                <a href="{{ route('admin.students') }}" class="{{ request()->routeIs('admin.students') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; text-decoration: none;">
                    <i class="fas fa-users" style="font-size: 1.1rem; width: 22px; text-align: center;"></i>
                    <span>إدارة الطلاب</span>
                </a>
            </li>
            
            <li style="margin-bottom: 5px;">
                <a href="{{ route('activities.index') }}" class="{{ request()->routeIs('activities.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; text-decoration: none;">
                    <i class="fas fa-calendar-alt" style="font-size: 1.1rem; width: 22px; text-align: center;"></i>
                    <span>إدارة الأنشطة</span>
                </a>
            </li>
            
            <li style="margin-bottom: 5px;">
                <a href="{{ route('admin.staff.index') }}" class="{{ request()->routeIs('admin.staff.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; text-decoration: none;">
                    <i class="fas fa-chalkboard-teacher" style="font-size: 1.1rem; width: 22px; text-align: center;"></i>
                    <span>إدارة الكادر</span>
                </a>
            </li>
            
            <li style="padding: 15px 16px 8px; font-size: 0.7rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase;" class="section-title">
                التسجيلات
            </li>
            
            <li style="margin-bottom: 5px;">
                <a href="{{ route('admin.all-registrations') }}" class="{{ request()->routeIs('admin.all-registrations') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; text-decoration: none;">
                    <i class="fas fa-list-alt" style="font-size: 1.1rem; width: 22px; text-align: center;"></i>
                    <span>جميع التسجيلات</span>
                </a>
            </li>
            
            <li style="padding: 15px 16px 8px; font-size: 0.7rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase;" class="section-title">
                الاستبيانات
            </li>
            
            <li style="margin-bottom: 5px;">
                <a href="{{ route('admin.survey-questions.index') }}" class="{{ request()->routeIs('admin.survey-questions.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; text-decoration: none;">
                    <i class="fas fa-poll" style="font-size: 1.1rem; width: 22px; text-align: center;"></i>
                    <span>إدارة الاستبيان</span>
                </a>
            </li>
            
            <li style="margin-bottom: 5px;">
                <a href="{{ route('admin.survey-stats.index') }}" class="{{ request()->routeIs('admin.survey-stats.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; text-decoration: none;">
                    <i class="fas fa-chart-bar" style="font-size: 1.1rem; width: 22px; text-align: center;"></i>
                    <span>إحصائيات وتقارير</span>
                </a>
            </li>
            
            <li style="padding: 15px 16px 8px; font-size: 0.7rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase;" class="section-title">
                التواصل
            </li>
            
            <li style="margin-bottom: 5px;">
                <a href="{{ route('admin.announcements') }}" class="{{ request()->routeIs('admin.announcements') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; text-decoration: none;">
                    <i class="fas fa-bullhorn" style="font-size: 1.1rem; width: 22px; text-align: center;"></i>
                    <span>الإعلانات والتبليغات</span>
                </a>
            </li>
            
            <li style="margin-bottom: 5px;">
                <a href="#" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; text-decoration: none;">
                    <i class="fas fa-cog" style="font-size: 1.1rem; width: 22px; text-align: center;"></i>
                    <span>الإعدادات العامة</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <!-- الفوتر -->
    <div class="sidebar-footer" style="padding: 20px 15px; border-top: 1px solid rgba(255,255,255,0.1);">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn" style="width: 100%; padding: 12px; border: none; border-radius: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; font-size: 0.95rem; font-weight: 600;">
                <i class="fas fa-sign-out-alt"></i>
                <span>تسجيل الخروج</span>
            </button>
        </form>
    </div>
</aside>