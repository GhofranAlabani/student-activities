<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة التسجيلات - {{ $activity->title }}</title>
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
                <a href="{{ route('staff.activities.show', $activity->id) }}" class="text-gray-300 hover:text-gold text-sm">
                    <i class="fas fa-arrow-right ml-1"></i> رجوع للتفاصيل
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-8 max-w-6xl">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500 text-lg"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- عنوان الصفحة -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-black text-navy flex items-center gap-2">
                <i class="fas fa-users text-gold"></i>
                إدارة تسجيلات: {{ $activity->title }}
            </h2>
            <p class="text-gray-500 mt-2">إجمالي المسجلين: {{ $registrations->total() }}</p>
        </div>

        <!-- قائمة التسجيلات -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            @if($registrations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-right text-sm font-bold text-navy">#</th>
                                <th class="px-6 py-4 text-right text-sm font-bold text-navy">الطالب</th>
                                <th class="px-6 py-4 text-right text-sm font-bold text-navy">البريد الإلكتروني</th>
                                <th class="px-6 py-4 text-right text-sm font-bold text-navy">تاريخ التسجيل</th>
                                <th class="px-6 py-4 text-right text-sm font-bold text-navy">الحالة</th>
                                <th class="px-6 py-4 text-right text-sm font-bold text-navy">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($registrations as $index => $registration)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $registrations->firstItem() + $index }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-navy rounded-full flex items-center justify-center text-white font-bold">
                                                {{ substr($registration->user->name ?? 'م', 0, 1) }}
                                            </div>
                                            <span class="font-bold text-navy">{{ $registration->user->name ?? 'غير معروف' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $registration->user->email ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $registration->created_at->format('Y/m/d H:i') }}</td>
                                    <td class="px-6 py-4">
                                        @if(isset($registration->status))
                                            @if($registration->status === 'approved')
                                                <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-bold">
                                                    <i class="fas fa-check-circle ml-1"></i>مقبول
                                                </span>
                                            @elseif($registration->status === 'rejected')
                                                <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full font-bold">
                                                    <i class="fas fa-times-circle ml-1"></i>مرفوض
                                                </span>
                                            @else
                                                <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full font-bold">
                                                    <i class="fas fa-clock ml-1"></i>معلق
                                                </span>
                                            @endif
                                        @else
                                            <span class="bg-gray-100 text-gray-600 text-xs px-3 py-1 rounded-full font-bold">غير محدد</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            @if(!isset($registration->status) || $registration->status === 'pending')
                                                <form action="{{ route('staff.registrations.approve', $registration->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600 transition text-sm" title="قبول">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('staff.registrations.reject', $registration->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition text-sm" title="رفض">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-400">تمت المعالجة</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-6">
                    {{ $registrations->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-user-slash text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">لا يوجد طلاب مسجلين في هذا النشاط بعد</p>
                </div>
            @endif
        </div>

    </div>

</body>
</html>