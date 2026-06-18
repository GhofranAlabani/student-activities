<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كل الإشعارات - لوحة الطالب</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .bg-navy { background-color: #0a1929; }
        .text-gold { color: #d4a017; }
        .bg-gold { background-color: #d4a017; }
        .border-gold { border-color: #d4a017; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#f5f0e8] min-h-screen flex flex-col">

    <!-- Header بسيط للصفحة -->
    <header class="bg-white shadow-md p-4 border-b-2 border-gold/20">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2 text-navy hover:text-gold transition font-bold">
                <i class="fas fa-arrow-right"></i> العودة للوحة التحكم
            </a>
            <h1 class="text-xl font-black text-navy">صندوق الإشعارات</h1>
        </div>
    </header>

    <!-- المحتوى الرئيسي -->
    <main class="flex-grow container mx-auto px-4 py-8 max-w-4xl">
        
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            @forelse($notifications as $notif)
                <div class="p-5 border-b border-gray-100 hover:bg-gray-50 transition flex items-start gap-4 {{ !$notif->is_read ? 'bg-blue-50/30' : '' }}">
                    
                    <!-- الأيقونة -->
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <i class="fas fa-{{ $notif->icon ?? 'bell' }} text-indigo-600 text-lg"></i>
                    </div>

                    <!-- التفاصيل -->
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold text-navy">{{ $notif->title }}</h3>
                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed mb-3">{{ $notif->message }}</p>
                        
                        <!-- زر الإجراء -->
                        @if($notif->activity_id)
                            <a href="{{ route('activities.show', $notif->activity_id) }}" 
                               class="inline-flex items-center gap-2 text-xs font-bold text-gold hover:text-yellow-700 transition">
                                عرض تفاصيل النشاط <i class="fas fa-arrow-left"></i>
                            </a>
                        @endif
                    </div>

                    <!-- حالة القراءة / الحذف -->
                    <div class="flex flex-col gap-2">
                        @if(!$notif->is_read)
                            <form action="{{ route('notifications.read', $notif->id) }}" method="GET">
                                <button type="submit" class="text-xs bg-green-100 text-green-700 px-3 py-1.5 rounded-lg hover:bg-green-200 transition font-bold" title="تعليم كمقروء">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-gray-400 px-3 py-1.5"><i class="fas fa-check-double"></i> مقروء</span>
                        @endif
                        
                        <form action="{{ url('/notifications/delete/' . $notif->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الإشعار؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs bg-red-50 text-red-600 px-3 py-1.5 rounded-lg hover:bg-red-100 transition font-bold" title="حذف الإشعار">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-gray-400">
                    <i class="fas fa-inbox text-5xl mb-4 opacity-30"></i>
                    <p class="text-lg font-bold">لا توجد إشعارات لعرضها</p>
                    <p class="text-sm mt-2">ستظهر هنا جميع تنبيهات الأنشطة والتحديثات</p>
                </div>
            @endforelse

            <!-- الترقيم (Pagination) -->
            @if($notifications->hasPages())
                <div class="p-4 bg-gray-50 border-t border-gray-100">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </main>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>