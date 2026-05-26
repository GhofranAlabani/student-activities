<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الطلاب</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #f8fafc; }
        .sidebar-link:hover { background-color: #e0e7ff; color: #4338ca; }
        .sidebar-link.active { background-color: #e0e7ff; color: #4338ca; font-weight: bold; }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <aside class="w-64 bg-white shadow-xl hidden md:flex flex-col z-10 border-l border-gray-100">
        <div class="p-6 bg-indigo-600 text-center shadow-lg">
            <div class="w-16 h-16 bg-white rounded-full mx-auto flex items-center justify-center mb-3 shadow-md">
                <i class="fas fa-user-shield text-3xl text-indigo-600"></i>
            </div>
            <h2 class="text-xl font-bold text-white">لوحة المدير</h2>
        </div>
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center p-3 text-gray-600 rounded-xl transition duration-200">
                <i class="fas fa-home ml-3 text-lg"></i> الرئيسية
            </a>
            <a href="{{ route('activities.index') }}" class="sidebar-link flex items-center p-3 text-gray-600 rounded-xl transition duration-200">
                <i class="fas fa-calendar-alt ml-3 text-lg"></i> الأنشطة
            </a>
            <a href="{{ route('admin.students') }}" class="sidebar-link active flex items-center p-3 rounded-xl transition duration-200">
                <i class="fas fa-users ml-3 text-lg"></i> الطلاب
            </a>
            <a href="{{ route('profile.edit') }}" class="sidebar-link flex items-center p-3 text-gray-600 rounded-xl transition duration-200">
                <i class="fas fa-cog ml-3 text-lg"></i> الإعدادات
            </a>
        </nav>
        <div class="p-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center p-2 text-red-500 hover:bg-red-50 rounded-lg transition font-bold">
                    <i class="fas fa-sign-out-alt ml-2"></i> تسجيل الخروج
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm p-4 flex justify-between items-center">
            <h1 class="font-bold text-xl text-indigo-700">إدارة الطلاب</h1>
            <span class="text-gray-500 bg-gray-50 px-4 py-2 rounded-full text-sm border border-gray-100">
                <i class="fas fa-calendar-alt ml-1 text-indigo-500"></i> {{ now()->format('Y/m/d') }}
            </span>
        </header>

        <main class="flex-1 overflow-y-auto p-6 md:p-8">
            <div class="max-w-7xl mx-auto">

                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
                    </div>
                @endif

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="bg-indigo-100 p-4 rounded-full">
                            <i class="fas fa-user-graduate text-indigo-600 text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">إجمالي المستخدمين</p>
                            <p class="text-3xl font-extrabold text-gray-800">{{ $students->total() }}</p>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="bg-green-100 p-4 rounded-full">
                            <i class="fas fa-user-check text-green-600 text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">طلاب</p>
                            <p class="text-3xl font-extrabold text-gray-800">{{ $activeStudents ?? $students->total() }}</p>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="bg-purple-100 p-4 rounded-full">
                            <i class="fas fa-user-shield text-purple-600 text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">مدراء</p>
                            <p class="text-3xl font-extrabold text-gray-800">{{ \App\Models\User::where('role', 'admin')->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
                    <form method="GET" action="{{ route('admin.students') }}" class="flex flex-col md:flex-row gap-3">
                        <div class="flex-1 relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="ابحث بالاسم أو البريد الإلكتروني..."
                                class="w-full pr-10 pl-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition font-semibold">
                            <i class="fas fa-filter ml-2"></i> بحث
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.students') }}" class="bg-gray-100 text-gray-600 px-6 py-3 rounded-lg hover:bg-gray-200 transition font-semibold">
                                <i class="fas fa-times ml-1"></i> مسح
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    @if($students->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-4 text-right font-bold text-gray-600 text-sm">#</th>
                                        <th class="px-6 py-4 text-right font-bold text-gray-600 text-sm">المستخدم</th>
                                        <th class="px-6 py-4 text-right font-bold text-gray-600 text-sm">البريد الإلكتروني</th>
                                        <th class="px-6 py-4 text-right font-bold text-gray-600 text-sm">الصلاحية</th>
                                        <th class="px-6 py-4 text-right font-bold text-gray-600 text-sm">الأنشطة</th>
                                        <th class="px-6 py-4 text-right font-bold text-gray-600 text-sm">تاريخ التسجيل</th>
                                        <th class="px-6 py-4 text-right font-bold text-gray-600 text-sm">تغيير الصلاحية</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($students as $index => $student)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 text-gray-500 text-sm">{{ $students->firstItem() + $index }}</td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 {{ $student->role === 'admin' ? 'bg-purple-500' : 'bg-indigo-500' }} rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                        {{ substr($student->name, 0, 1) }}
                                                    </div>
                                                    <span class="font-semibold text-gray-800">{{ $student->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-gray-600 text-sm">{{ $student->email }}</td>
                                            <td class="px-6 py-4">
                                                @if($student->role === 'admin')
                                                    <span class="bg-purple-50 text-purple-700 px-3 py-1 rounded-full text-sm font-bold border border-purple-100">
                                                        <i class="fas fa-user-shield ml-1"></i> مدير
                                                    </span>
                                                @else
                                                    <span class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-sm font-bold border border-indigo-100">
                                                        <i class="fas fa-user-graduate ml-1"></i> طالب
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-sm font-bold border border-indigo-100">
                                                    {{ $student->activities->count() }} نشاط
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-gray-500 text-sm">
                                                {{ $student->created_at->format('Y/m/d') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <form method="POST" action="{{ route('admin.user.role', $student->id) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="role" onchange="this.form.submit()"
                                                        class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                                                        <option value="student" {{ $student->role === 'student' ? 'selected' : '' }}>طالب</option>
                                                        <option value="admin" {{ $student->role === 'admin' ? 'selected' : '' }}>مدير</option>
                                                        <option value="supervisor" {{ $student->role === 'supervisor' ? 'selected' : '' }}>مشرف</option>
                                                    </select>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-5 border-t border-gray-100">
                            {{ $students->links() }}
                        </div>
                    @else
                        <div class="p-16 text-center">
                            <i class="fas fa-user-slash text-6xl text-gray-200 mb-4"></i>
                            <p class="text-gray-500 text-xl font-semibold">لا يوجد مستخدمون مطابقون للبحث</p>
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

</body>
</html>