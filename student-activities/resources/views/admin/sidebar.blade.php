<aside class="sidebar">
    <div class="sidebar-header">
        <h3>لوحة تحكم الإدارة</h3>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <!-- الرئيسية -->
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>الرئيسية</span>
                </a>
            </li>
            
            <!-- إدارة الطلاب -->
            <li>
                <a href="{{ route('admin.students') }}" class="{{ request()->routeIs('admin.students') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>إدارة الطلاب</span>
                </a>
            </li>
            
            <!-- إدارة الأنشطة -->
            <li>
                <a href="{{ route('activities.index') }}" class="{{ request()->routeIs('activities.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>إدارة الأنشطة</span>
                </a>
            </li>
            
            <!-- 📋 إدارة الاستبيانات (جديد) -->
            <li>
                <a href="{{ route('admin.survey-questions.index') }}" 
            class="{{ request()->routeIs('admin.survey-questions.*') ? 'active' : '' }}">
          <i class="fas fa-poll"></i>
          <span>إدارة الاستبيان</span>
                </a>
            </li>
            
            <!-- إدارة الكادر -->
            <li>
                <a href="{{ route('admin.staff') }}" class="{{ request()->routeIs('admin.staff') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>إدارة الكادر</span>
                </a>
            </li>
            
            <!-- جميع التسجيلات -->
            <li>
                <a href="{{ route('admin.all-registrations') }}" class="{{ request()->routeIs('admin.all-registrations') ? 'active' : '' }}">
                    <i class="fas fa-list-alt"></i>
                    <span>جميع التسجيلات</span>
                </a>
            </li>
            
            <!-- الإعلانات والتبليغات -->
            <li>
                <a href="{{ route('admin.announcements') }}" class="{{ request()->routeIs('admin.announcements') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn"></i>
                    <span>الإعلانات والتبليغات</span>
                </a>
            </li>
            
            
            
            <!-- الإعدادات العامة -->
            <li>
                <a href="#" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>الإعدادات العامة</span>
                </a>
            </li><li>
    <a href="{{ route('admin.survey-stats.index') }}" 
       class="{{ request()->routeIs('admin.survey-stats.*') ? 'active' : '' }}">
        <i class="fas fa-chart-bar"></i>
        <span>إحصائيات وتقارير</span>
    </a>
</li>
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>تسجيل الخروج</span>
            </button>
        </form>
    </div>
</aside>