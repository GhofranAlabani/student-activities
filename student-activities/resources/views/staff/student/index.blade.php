<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الطلاب المسجلين - لوحة المشرف</title>
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
                <a href="{{ route('staff.students.index') }}" class="sidebar-link active flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-users text-gold w-5"></i>
                    <span class="font-bold">الطلاب المسجلين</span>
                </a>
                <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
                    <i class="fas fa-chart-bar text-gold w-5"></i>
                    <span class="font-bold">التقارير</span>
                </a>
                <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white">
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
                        <h1 class="text-2xl font-black text-navy">الطلاب المسجلين</h1>
                        <p class="text-sm text-gray-500 mt-1">إدارة الطلاب المسجلين في أنشطتك</p>
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
                
                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border-r-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">إجمالي الطلاب</p>
                                <h3 class="text-3xl font-black text-navy mt-1">{{ $totalStudents }}</h3>
                            </div>
                            <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-users text-blue-500 text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6 border-r-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">إجمالي التسجيلات</p>
                                <h3 class="text-3xl font-black text-navy mt-1">{{ $totalRegistrations }}</h3>
                            </div>
                            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-clipboard-list text-green-500 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Students Table -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-black text-navy mb-6 flex items-center gap-2">
                        <i class="fas fa-users text-gold"></i>
                        قائمة الطلاب
                    </h2>

                    @if($students->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-right text-sm font-bold text-navy">#</th>
                                        <th class="px-4 py-3 text-right text-sm font-bold text-navy">الطالب</th>
                                        <th class="px-4 py-3 text-right text-sm font-bold text-navy">البريد الإلكتروني</th>
                                        <th class="px-4 py-3 text-right text-sm font-bold text-navy">عدد التسجيلات</th>
                                        <th class="px-4 py-3 text-right text-sm font-bold text-navy">تاريخ الانضمام</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($students as $index => $student)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-4 text-sm text-gray-600">{{ $students->firstItem() + $index }}</td>
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-navy rounded-full flex items-center justify-center text-white font-bold">
                                                        {{ substr($student->name, 0, 1) }}
                                                    </div>
                                                    <span class="font-bold text-navy">{{ $student->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-600">{{ $student->email }}</td>
                                            <td class="px-4 py-4">
                                                <span class="bg-gold/20 text-navy text-sm px-3 py-1 rounded-full font-bold">
                                                    {{ $student->registrations->count() }} تسجيل
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-600">
                                                {{ $student->created_at->format('Y/m/d') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $students->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-user-slash text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg mb-4">لا يوجد طلاب مسجلين في أنشطتك بعد</p>
                            <a href="{{ route('staff.activities.create') }}" class="inline-block bg-gold text-navy px-6 py-3 rounded-xl font-bold hover:bg-yellow-600 transition">
                                <i class="fas fa-plus ml-2"></i> أضف نشاط جديد
                            </a>
                        </div>
                    @endif
                </div>

            </main>
        </div>

    </div>

</body>
</html>