<aside class="w-72 bg-navy text-white fixed right-0 top-0 h-full shadow-2xl z-50 overflow-y-auto">
    <div class="p-6 border-b border-white/10">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gold rounded-xl flex items-center justify-center text-navy font-black text-xl shadow-lg">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gold">لوحة الطالب</h2>
                <p class="text-xs text-gray-400">{{ auth()->user()->name }}</p>
            </div>
        </div>
    </div>

    <nav class="p-4 space-y-2">
        <a href="{{ route('student.dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
            <i class="fas fa-home text-gold w-5"></i>
            <span class="font-bold">الرئيسية</span>
        </a>
        <a href="{{ route('activities.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
            <i class="fas fa-calendar-alt text-gold w-5"></i>
            <span class="font-bold">الأنشطة</span>
        </a>
        <a href="{{ route('student.my-activities') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
            <i class="fas fa-tasks text-gold w-5"></i>
            <span class="font-bold">أنشطتي</span>
        </a>
        <a href="{{ route('student.favorites') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
            <i class="fas fa-heart text-gold w-5"></i>
            <span class="font-bold">المفضلة</span>
        </a>
        <a href="{{ route('attendance.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
            <i class="fas fa-clipboard-list text-gold w-5"></i>
            <span class="font-bold">سجل الحضور</span>
        </a>
        <a href="{{ route('attendance.scan') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white bg-gold/10">
            <i class="fas fa-qrcode text-gold w-5"></i>
            <span class="font-bold">تسجيل الحضور</span>
        </a>
        <div class="border-t border-white/10 my-4"></div>
        <a href="{{ route('student.profile') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
            <i class="fas fa-user text-gold w-5"></i>
            <span class="font-bold">الملف الشخصي</span>
        </a>
    </nav>

    <div class="absolute bottom-0 right-0 left-0 p-4 border-t border-white/10 bg-navy-light">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-400 hover:bg-red-500/10 transition">
                <i class="fas fa-sign-out-alt w-5"></i>
                <span class="font-bold">تسجيل الخروج</span>
            </button>
        </form>
    </div>
</aside>