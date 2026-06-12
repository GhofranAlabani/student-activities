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
    </style>
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
            <div class="flex items-center gap-4 mr-auto">
                <span class="text-gray-700 bg-gold/10 px-4 py-2 rounded-full text-sm shadow-sm border border-gold/30">
                    <i class="fas fa-calendar-alt ml-1 text-gold"></i> {{ now()->format('Y/m/d') }}
                </span>
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-gold rounded-full flex items-center justify-center text-navy font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <span class="text-gray-800 font-semibold hidden md:inline">{{ auth()->user()->name }}</span>
                </div>
            </div>
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