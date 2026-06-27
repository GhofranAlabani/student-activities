<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة المشرف</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .bg-navy { background-color: #0a1929; }
        .text-gold { color: #d4a017; }
        .bg-gold { background-color: #d4a017; }
        .bg-gold:hover { background-color: #b8860b; }
    </style>
</head>
<body class="bg-[#f5f0e8] min-h-screen">

    <!-- Navbar -->
    <nav class="bg-navy text-white shadow-lg">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gold rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-shield text-navy"></i>
                </div>
                <div>
                    <h1 class="font-bold text-gold">لوحة المشرف</h1>
                    <p class="text-xs text-gray-300">{{ auth()->user()->name }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('student.dashboard') }}" class="text-gray-300 hover:text-gold text-sm">
                    <i class="fas fa-arrow-right ml-1"></i> لوحة الطالب
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-red-400 hover:text-red-300 text-sm">
                        <i class="fas fa-sign-out-alt ml-1"></i> خروج
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-6 py-8">
        
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6">
            <i class="fas fa-check-circle text-green-500 text-lg ml-2"></i>
            <strong>نجح!</strong> أنت الآن في لوحة المشرف!
        </div>

        <!-- إحصائيات -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-lg border-r-4 border-gold">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">أنشطتي</p>
                        <h3 class="text-3xl font-black text-navy">{{ $myActivitiesCount ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-gold/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar text-gold text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border-r-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">تسجيلات معلقة</p>
                        <h3 class="text-3xl font-black text-navy">{{ $pendingRegistrations->count() ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-blue-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border-r-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">إجمالي المشاركين</p>
                        <h3 class="text-3xl font-black text-navy">{{ $totalParticipants ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-green-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- رسالة توضيحية -->
        <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
            <i class="fas fa-user-shield text-6xl text-gold mb-4"></i>
            <h2 class="text-2xl font-black text-navy mb-2">مرحباً بك في لوحة المشرف!</h2>
            <p class="text-gray-600 mb-6">يمكنك الآن إدارة أنشطتك ومتابعة التسجيلات</p>
            
            <div class="flex gap-4 justify-center">
                <a href="{{ route('staff.activities.index') }}" class="bg-gold text-navy px-6 py-3 rounded-xl font-bold hover:bg-yellow-600 transition">
                    <i class="fas fa-calendar-plus ml-2"></i> إدارة الأنشطة
                </a>
                <a href="{{ route('staff.activities.create') }}" class="bg-navy text-white px-6 py-3 rounded-xl font-bold hover:bg-gold hover:text-navy transition">
                    <i class="fas fa-plus ml-2"></i> إضافة نشاط
                </a>
            </div>
        </div>

    </div>

</body>
</html>