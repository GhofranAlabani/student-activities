<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الإعلانات - لوحة المشرف</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .bg-navy { background-color: #0a1929; }
        .bg-navy-light { background-color: #112240; }
        .text-gold { color: #d4a017; }
        .bg-gold { background-color: #d4a017; }
        .bg-gold:hover { background-color: #b8860b; }
        .sidebar-link { transition: all 0.3s ease; }
        .sidebar-link:hover { background-color: rgba(212, 160, 23, 0.1); transform: translateX(-5px); }
        .sidebar-link.active { background-color: rgba(212, 160, 23, 0.2); border-right: 3px solid #d4a017; }
    </style>
</head>
<body class="bg-[#f5f0e8] min-h-screen">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-72 bg-navy text-white fixed right-0 top-0 h-full shadow-2xl z-50 overflow-y-auto">
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gold rounded-xl flex items-center justify-center text-navy font-black text-xl shadow-lg">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gold">لوحة المشرف</h2>
                        <p class="text-xs text-gray-400">{{ auth()->user()->name }}</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-2">
                <a href="{{ route('staff.dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-home text-gold w-5"></i>
                    <span class="font-bold">الرئيسية</span>
                </a>
                <a href="{{ route('staff.activities.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-calendar-alt text-gold w-5"></i>
                    <span class="font-bold">أنشطتي</span>
                </a>
                <a href="{{ route('staff.activities.create') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-plus-circle text-gold w-5"></i>
                    <span class="font-bold">إضافة نشاط</span>
                </a>
                <a href="{{ route('staff.students.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-users text-gold w-5"></i>
                    <span class="font-bold">الطلاب المسجلين</span>
                </a>
                <a href="{{ route('staff.reports.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-chart-bar text-gold w-5"></i>
                    <span class="font-bold">التقارير</span>
                </a>
                <a href="{{ route('staff.announcements.index') }}" class="sidebar-link active flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-bullhorn text-gold w-5"></i>
                    <span class="font-bold">الإعلانات</span>
                </a>
                <div class="border-t border-white/10 my-4"></div>
                <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-cog text-gray-400 w-5"></i>
                    <span class="font-bold">الإعدادات</span>
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

        <!-- Main Content -->
        <div class="flex-1 mr-72">
            
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 sticky top-0 z-40">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-black text-navy">إدارة الإعلانات</h1>
                        <p class="text-sm text-gray-500 mt-1">نشر وإدارة الإعلانات للطلاب</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-left">
                            <p class="font-bold text-navy">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">مشرف أنشطة</p>
                        </div>
                        <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center text-navy font-bold shadow-lg">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-8">
                
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- نموذج إضافة إعلان -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                            <h2 class="text-xl font-black text-navy mb-6 flex items-center gap-2">
                                <i class="fas fa-plus-circle text-gold"></i>
                                إعلان جديد
                            </h2>

                            <form action="{{ route('staff.announcements.store') }}" method="POST" class="space-y-4">
                                @csrf

                                <div>
                                    <label class="block text-navy font-bold mb-2 text-sm">عنوان الإعلان *</label>
                                    <input type="text" name="title" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold">
                                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-navy font-bold mb-2 text-sm">المحتوى *</label>
                                    <textarea name="content" rows="4" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold"></textarea>
                                    @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-navy font-bold mb-2 text-sm">نوع الإعلان *</label>
                                    <select name="type" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold">
                                        <option value="general">عام</option>
                                        <option value="urgent">عاجل</option>
                                        <option value="info">معلوماتي</option>
                                    </select>
                                </div>

                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" checked class="w-4 h-4 text-gold rounded">
                                    <label for="is_active" class="text-navy font-bold text-sm">نشط (يظهر للطلاب)</label>
                                </div>

                                <button type="submit" class="w-full bg-gold text-navy py-3 rounded-xl font-bold hover:bg-yellow-600 transition shadow-lg">
                                    <i class="fas fa-paper-plane ml-2"></i> نشر الإعلان
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- قائمة الإعلانات -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h2 class="text-xl font-black text-navy mb-6 flex items-center gap-2">
                                <i class="fas fa-list text-gold"></i>
                                إعلاناتي ({{ $announcements->count() }})
                            </h2>

                            @if($announcements->count() > 0)
                                <div class="space-y-4">
                                    @foreach($announcements as $announcement)
                                        <div class="border border-gray-100 rounded-xl p-5 hover:shadow-md transition {{ !$announcement->is_active ? 'opacity-60 bg-gray-50' : 'bg-white' }}">
                                            <div class="flex justify-between items-start mb-3">
                                                <div class="flex items-center gap-3">
                                                    @if($announcement->type === 'urgent')
                                                        <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full font-bold">
                                                            <i class="fas fa-exclamation-circle ml-1"></i>عاجل
                                                        </span>
                                                    @elseif($announcement->type === 'info')
                                                        <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-bold">
                                                            <i class="fas fa-info-circle ml-1"></i>معلوماتي
                                                        </span>
                                                    @else
                                                        <span class="bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full font-bold">
                                                            <i class="fas fa-bullhorn ml-1"></i>عام
                                                        </span>
                                                    @endif

                                                    @if($announcement->is_active)
                                                        <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-bold">نشط</span>
                                                    @else
                                                        <span class="bg-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full font-bold">غير نشط</span>
                                                    @endif
                                                </div>
                                                
                                                <form action="{{ route('staff.announcements.destroy', $announcement->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الإعلان؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <h3 class="text-lg font-black text-navy mb-2">{{ $announcement->title }}</h3>
                                            <p class="text-gray-600 text-sm leading-relaxed mb-3">{{ $announcement->content }}</p>
                                            
                                            <div class="text-xs text-gray-400">
                                                <i class="fas fa-clock ml-1"></i>
                                                {{ $announcement->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <i class="fas fa-bullhorn text-6xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500 text-lg">لا توجد إعلانات حتى الآن</p>
                                    <p class="text-gray-400 text-sm mt-2">ابدأ بنشر أول إعلان للطلاب!</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </main>
        </div>

    </div>

</body>
</html>