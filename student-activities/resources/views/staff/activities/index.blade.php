<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أنشطتي - لوحة المشرف</title>
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
                <a href="{{ route('staff.dashboard') }}" class="text-gray-300 hover:text-gold text-sm">
                    <i class="fas fa-home ml-1"></i> الرئيسية
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

    <div class="container mx-auto px-6 py-8">
        
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500 text-lg"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-black text-navy">أنشطتي</h2>
            <a href="{{ route('staff.activities.create') }}" class="bg-gold text-navy px-6 py-3 rounded-xl font-bold hover:bg-yellow-600 transition">
                <i class="fas fa-plus ml-2"></i> إضافة نشاط جديد
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            @if($activities->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($activities as $activity)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition">
                            <div class="h-48 bg-gradient-to-br from-navy to-navy-light flex items-center justify-center relative">
                                @if($activity->image)
                                    <img src="{{ asset('storage/' . $activity->image) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-calendar-alt text-6xl text-white/30"></i>
                                @endif
                                <span class="absolute top-3 right-3 bg-gold text-navy text-xs font-bold px-3 py-1 rounded-full">
                                    {{ $activity->activityType->name ?? 'عام' }}
                                </span>
                            </div>
                            <div class="p-6">
                                <h3 class="font-bold text-navy text-lg mb-2">{{ $activity->title }}</h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($activity->description, 80) }}</p>
                                
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-sm text-gray-500">
                                        <i class="fas fa-calendar text-gold ml-1"></i>
                                        {{ \Carbon\Carbon::parse($activity->date)->format('Y/m/d') }}
                                    </span>
                                    <span class="text-sm font-bold text-navy">
                                        {{ $activity->registrations_count }} مسجل
                                    </span>
                                </div>

                                <div class="flex gap-2">
                                    <a href="{{ route('staff.activities.show', $activity->id) }}" class="flex-1 bg-navy text-white text-center py-2 rounded-lg hover:bg-gold hover:text-navy transition text-sm font-bold">
                                        <i class="fas fa-eye ml-1"></i> التفاصيل
                                    </a>
                                    <a href="{{ route('staff.activities.edit', $activity->id) }}" class="bg-gold text-navy px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $activities->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg mb-4">لم تضف أي نشاط بعد</p>
                    <a href="{{ route('staff.activities.create') }}" class="bg-gold text-navy px-6 py-3 rounded-xl font-bold hover:bg-yellow-600 transition">
                        <i class="fas fa-plus ml-2"></i> إضافة أول نشاط
                    </a>
                </div>
            @endif
        </div>

    </div>

</body>
</html>