<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الطالب</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .bg-navy { background-color: #0a1929; }
        .bg-navy-light { background-color: #112240; }
        .text-gold { color: #d4a017; }
        .bg-gold { background-color: #d4a017; }
        .bg-gold:hover { background-color: #b8860b; }
        .border-gold { border-color: #d4a017; }
        .sidebar-link:hover { background-color: rgba(212, 160, 23, 0.1); color: #d4a017; }
        .sidebar-link.active { background-color: rgba(212, 160, 23, 0.2); color: #d4a017; font-weight: bold; border-right: 3px solid #d4a017; }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(212, 160, 23, 0.15); }
              
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #d4a017; border-radius: 20px; }
        .bg-navy { background-color: #0a1929; }
.text-gold { color: #d4a017; }
.bg-gold { background-color: #d4a017; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="flex h-screen overflow-hidden bg-[#f5f0e8]">

    <!-- الشريط الجانبي (Sidebar) -->
    <aside class="w-64 bg-navy shadow-2xl hidden md:flex flex-col z-10 border-l border-gold/20">
        <div class="p-6 bg-navy-light text-center shadow-lg border-b border-gold/20">
            <div class="w-16 h-16 bg-gold rounded-full mx-auto flex items-center justify-center mb-3 shadow-xl">
                <i class="fas fa-user-graduate text-3xl text-navy"></i>
            </div>
            <h2 class="text-xl font-bold text-gold">لوحة الطالب</h2>
        </div>
        
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <a href="{{ route('student.dashboard') }}" class="sidebar-link active flex items-center p-3 rounded-xl transition duration-200 text-gold">
                <i class="fas fa-home ml-3 text-lg"></i> الرئيسية
            </a>
            <a href="{{ route('student.my-activities') }}" class="sidebar-link flex items-center p-3 text-gray-300 rounded-xl transition duration-200">
                <i class="fas fa-calendar-check ml-3 text-lg"></i> أنشطتي
            </a>
            <a href="{{ route('student.favorites') }}" class="sidebar-link flex items-center p-3 text-gray-300 rounded-xl transition duration-200">
                <i class="fas fa-heart ml-3 text-lg"></i> المفضلة
            
</a>

<a href="{{ route('staff.attendance.index', $activity->id ?? 1) }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
    <i class="fas fa-clipboard-check text-gold w-5"></i>
    <span class="font-bold">الحضور</span>
</a> 

<a href="{{ route('attendance.scan') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
    <i class="fas fa-qrcode text-gold w-5"></i>
    <span class="font-bold">تسجيل الحضور</span>
</a>
            <a href="{{ route('student.profile') }}" class="sidebar-link flex items-center p-3 text-gray-300 rounded-xl transition duration-200">
                <i class="fas fa-user ml-3 text-lg"></i> ملفي الشخصي
            </a>
            <a href="{{ route('profile.edit') }}" class="sidebar-link flex items-center p-3 text-gray-300 rounded-xl transition duration-200">
                <i class="fas fa-cog ml-3 text-lg"></i> الإعدادات
            </a>
        </nav>

        <div class="p-4 border-t border-gold/20">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center p-2 text-red-400 hover:bg-red-500/10 rounded-lg transition font-bold">
                    <i class="fas fa-sign-out-alt ml-2"></i> تسجيل الخروج
                </button>
            </form>
        </div>
    </aside>

    <!-- المحتوى الرئيسي -->
    <div class="flex-1 flex flex-col overflow-hidden relative">
        
              <header class="bg-white shadow-md p-4 flex justify-between items-center border-b-2 border-gold/20">
            <h1 class="font-bold text-xl text-navy md:hidden">الطالب</h1>
            
            <div class="flex items-center gap-6 mr-auto">
                
               
<!-- 🔔 نظام الإشعارات المنسدل -->
<div class="relative" x-data="{ open: false, notifications: [], unreadCount: 0 }" 
     @click.outside="open = false"
     x-init="
        fetch('/notifications/latest')
            .then(r => r.json())
            .then(data => { notifications = data.notifications; unreadCount = data.unread_count; });
        setInterval(() => {
            fetch('/notifications/latest')
                .then(r => r.json())
                .then(data => { notifications = data.notifications; unreadCount = data.unread_count; });
        }, 30000);
     ">
    
   <!-- 🔔 نظام الإشعارات الكامل -->
<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    
    <!-- زر الجرس -->
    <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gold transition focus:outline-none">
        <i class="fas fa-bell text-xl"></i>
        
        @php
            $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
        @endphp
        
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full animate-pulse border-2 border-white">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- القائمة المنسدلة -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="absolute left-0 top-full mt-2 w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
        
        <!-- Header القائمة -->
        <div class="bg-gradient-to-r from-navy to-navy-light text-white p-4 flex items-center justify-between">
            <h3 class="font-bold flex items-center gap-2">
                <i class="fas fa-bell text-gold"></i>
                الإشعارات
            </h3>
            <span class="bg-gold/20 text-gold px-2 py-1 rounded-full text-xs font-bold">
                {{ $unreadCount }} جديد
            </span>
        </div>

        <!-- قائمة الإشعارات -->
        <div class="max-h-96 overflow-y-auto">
            @php
                $recentNotifications = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(10)->get();
            @endphp
            
            @forelse($recentNotifications as $notif)
                <a href="{{ route('notifications.read', $notif->id) }}" 
                   class="block p-4 border-b border-gray-100 hover:bg-gray-50 transition {{ !$notif->is_read ? 'bg-yellow-50/30' : '' }}">
                    <div class="flex items-start gap-3">
                        <!-- الأيقونة -->
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl flex-shrink-0"
                             style="background: {{ $notif->color ?? '#3b82f6' }}20;">
                            @if($notif->icon && strlen($notif->icon) <= 4)
                                <span>{{ $notif->icon }}</span>
                            @else
                                <i class="fas fa-{{ $notif->icon ?? 'bell' }} text-sm" style="color: {{ $notif->color ?? '#3b82f6' }}"></i>
                            @endif
                        </div>
                        
                        <!-- المحتوى -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between mb-1">
                                <h4 class="font-bold text-navy text-sm">{{ $notif->title }}</h4>
                                @if(!$notif->is_read)
                                    <span class="bg-gold/20 text-gold px-2 py-0.5 rounded-full text-xs font-bold">جديد</span>
                                @endif
                            </div>
                            <p class="text-gray-600 text-xs line-clamp-2">{{ $notif->message }}</p>
                            <p class="text-gray-400 text-xs mt-2">
                                <i class="far fa-clock"></i>
                                {{ $notif->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-8 text-center text-gray-400">
                    <i class="fas fa-bell-slash text-4xl mb-2 opacity-50"></i>
                    <p class="text-sm">ما في إشعارات</p>
                </div>
            @endforelse
        </div>

        <!-- Footer القائمة -->
        <div class="bg-gray-50 p-3 text-center border-t border-gray-200">
            <a href="{{ route('notifications.index') }}" class="text-gold font-bold text-sm hover:text-yellow-700 transition">
                <i class="fas fa-eye ml-1"></i> عرض كل الإشعارات
            </a>
        </div>
    </div>
</div>
<!-- نهاية قسم الإشعارات -->
        </header>

        <main class="flex-1 overflow-y-auto p-6 bg-[#f5f0e8]">
            <div class="max-w-6xl mx-auto">
                
                <div class="mb-8">
                    <h1 class="text-3xl font-black text-navy">
                        مرحباً بك يا {{ auth()->user()->name }} 🎓
                    </h1>
                    <p class="text-gray-600 mt-2">تصفح أنشطتك ومتابعة تقدمك</p>
                </div>

                <!-- ✅ قسم الإعلانات المعدّل -->
                @if(isset($announcements) && $announcements->count() > 0)
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-navy flex items-center gap-2">
                            <i class="fas fa-bullhorn text-gold text-2xl"></i>
                            آخر الإعلانات
                        </h2>
                        <span class="bg-gradient-to-r from-gold to-yellow-600 text-navy px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                            {{ $announcements->count() }} جديد
                        </span>
                    </div>

                    <div class="space-y-4">
                        @foreach($announcements as $announcement)
                        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border-t-4 
                            {{ $announcement->type === 'activity' ? 'border-green-500' : ($announcement->type === 'warning' ? 'border-red-500' : 'border-gold') }}">
                            
                            <div class="p-6">
                                <!-- رأس الإعلان -->
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-4 flex-1">
                                        <!-- العنوان -->
                                        <div class="flex-1">
                                            <h3 class="font-bold text-navy text-xl mb-1">
                                                {{ $announcement->title }}
                                            </h3>
                                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                                <i class="far fa-clock"></i>
                                                <span>{{ $announcement->created_at ? $announcement->created_at->diffForHumans() : 'منذ قليل' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- الأيقونة الدائرية -->
                                    <div class="w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0 mr-4
                                        {{ $announcement->type === 'activity' ? 'bg-green-500 text-white' : ($announcement->type === 'warning' ? 'bg-red-500 text-white' : 'bg-gold text-navy') }}">
                                        @if($announcement->type === 'activity')
                                            <i class="fas fa-calendar-alt text-2xl"></i>
                                        @elseif($announcement->type === 'warning')
                                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                                        @else
                                            <i class="fas fa-info-circle text-2xl"></i>
                                        @endif
                                    </div>
                                </div>

                                <!-- محتوى الإعلان -->
                                <p class="text-gray-600 text-sm leading-relaxed mb-4 line-clamp-2">
                                    {{ Str::limit($announcement->content, 150) }}
                                </p>

                                <!-- زر عرض النشاط -->
                                @if($announcement->activity)
                                          <a href="{{ route('activities.show', $announcement->activity->id) }}" 
                                   class="inline-flex items-center gap-2 text-sm font-bold 
                                   {{ $announcement->type === 'activity' ? 'text-green-600 hover:text-green-700' : ($announcement->type === 'warning' ? 'text-red-600 hover:text-red-700' : 'text-gold hover:text-yellow-700') }} transition-colors">
                                    عرض النشاط
                                    <i class="fas fa-arrow-left text-xs"></i>
                                           </a>
                                  @else
                                      <span class="inline-flex items-center gap-2 text-sm font-bold text-gray-500">
                                    <i class="fas fa-info-circle"></i>
                                              إعلان عام
                                      </span>
                                 @endif
                                </div>
                               </div>
                                 @endforeach
                                 </div>
                                </div>
                                   @endif

                <!-- البطاقات الإحصائية -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="card-hover bg-white p-6 rounded-2xl shadow-lg border-b-4 border-pink-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm mb-1 font-semibold">المفضلة</p>
                <h3 class="text-4xl font-black text-navy">{{ auth()->user()->favorites()->count() }}</h3>
            </div>
            <div class="bg-pink-100 p-4 rounded-full">
                <i class="fas fa-heart text-3xl text-pink-500"></i>
            </div>
        </div>
    </div>

    <div class="card-hover bg-white p-6 rounded-2xl shadow-lg border-b-4 border-gold">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm mb-1 font-semibold">مجموع النقاط</p>
                <h3 class="text-4xl font-black text-navy">{{ auth()->user()->points ?? 0 }}</h3>
            </div>
            <div class="bg-yellow-100 p-4 rounded-full">
                <i class="fas fa-star text-3xl text-gold"></i>
            </div>
        </div>
    </div>

    <div class="card-hover bg-white p-6 rounded-2xl shadow-lg border-b-4 border-indigo-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm mb-1 font-semibold">الأنشطة المشتركة</p>
                <h3 class="text-4xl font-black text-navy">{{ auth()->user()->activities()->count() }}</h3>
            </div>
            <div class="bg-indigo-100 p-4 rounded-full">
                <i class="fas fa-calendar-check text-3xl text-indigo-600"></i>
            </div>
        </div>
    </div>
</div>


<!-- الأزرار في الوسط -->
        <div class="flex justify-center items-center gap-6 mb-8">
          <a href="{{ route('student.my-activities') }}" 
           class="inline-flex items-center gap-3 bg-white border-2 border-navy text-navy px-8 py-4 rounded-xl hover:bg-navy hover:text-gold transition-all font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
        <i class="fas fa-calendar-check text-xl"></i>
        <span>أنشطتي المسجلة</span>
     </a>
    <a href="{{ route('activities.index') }}" 
       class="inline-flex items-center gap-3 bg-gold text-navy px-8 py-4 rounded-xl hover:bg-yellow-600 transition-all font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
        <i class="fas fa-search text-xl"></i>
        <span>تصفح الأنشطة</span>
    </a>
        </div>

            </div>
        </main>
    </div>

</body>
</html>