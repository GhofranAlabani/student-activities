<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الأنشطة - لوحة المشرف</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #f5f0e8; }
        .bg-navy { background-color: #0a1929; }
        .bg-navy-light { background-color: #112240; }
        .text-navy { color: #0a1929; }
        .text-gold { color: #d4a017; }
        .bg-gold { background-color: #d4a017; }
        .bg-gold:hover { background-color: #b8860b; }
        .border-gold { border-color: #d4a017; }
    </style>
</head>
<body>
    <div class="container mx-auto px-6 py-8">
        
        <!-- Header -->
        <header class="bg-navy rounded-2xl p-6 mb-8 shadow-lg flex justify-between items-center text-white">
            <div class="flex items-center gap-3">
                <i class="fas fa-calendar-check text-gold text-3xl"></i>
                <div>
                    <h1 class="text-2xl font-bold text-white mb-1">إدارة الأنشطة</h1>
                    <p class="text-gray-300 text-sm">عرض وإضافة وتعديل الأنشطة والفعاليات الطلابية</p>
                </div>
            </div>
            <div class="bg-gold/20 px-5 py-2 rounded-full text-sm font-semibold border border-gold/30">
                <i class="fas fa-calendar-alt ml-2 text-gold"></i> {{ now()->format('Y/m/d') }}
            </div>
        </header>

        <!-- بطاقات الإحصائيات -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 flex items-center gap-4 hover:shadow-xl transition">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl flex items-center justify-center text-white text-2xl">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 font-semibold">إجمالي الأنشطة</h3>
                    <p class="text-3xl font-bold text-navy">{{ $activities->count() ?? 0 }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 flex items-center gap-4 hover:shadow-xl transition">
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-700 rounded-xl flex items-center justify-center text-white text-2xl">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 font-semibold">الأنشطة المتاحة</h3>
                    <p class="text-3xl font-bold text-navy">{{ $activities->where('status', 'مفتوح')->count() ?? 0 }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 flex items-center gap-4 hover:shadow-xl transition">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center text-white text-2xl">
                    <i class="fas fa-user-check"></i>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 font-semibold">إجمالي التسجيلات</h3>
                    <p class="text-3xl font-bold text-navy">{{ $totalRegistrations ?? 0 }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 flex items-center gap-4 hover:shadow-xl transition">
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center text-white text-2xl">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 font-semibold">متوسط التقييم</h3>
                    <p class="text-3xl font-bold text-navy">{{ number_format($averageRating ?? 0, 1) }}</p>
                </div>
            </div>
        </div>

        <!-- حاوية الجدول -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <!-- شريط العنوان والإضافة -->
            <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
                <h2 class="text-xl font-bold text-navy">
                    <i class="fas fa-list text-gold ml-2"></i>
                    قائمة الأنشطة
                </h2>
                <a href="{{ route('staff.activities.create') }}" class="bg-gold text-navy px-6 py-3 rounded-xl font-bold hover:bg-yellow-600 transition shadow-lg flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    إضافة نشاط
                </a>
            </div>

            <!-- الفلاتر -->
            <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-200">
                <form method="GET" action="{{ route('staff.activities.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-navy mb-2">
                            <i class="fas fa-search text-gold ml-1"></i> البحث
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="ابحث باسم النشاط..."
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-navy mb-2">
                            <i class="fas fa-layer-group text-gold ml-1"></i> النوع
                        </label>
                        <select name="type" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                            <option value="">جميع الأنواع</option>
                            <option value="conference" {{ request('type') == 'conference' ? 'selected' : '' }}>مؤتمر</option>
                            <option value="workshop" {{ request('type') == 'workshop' ? 'selected' : '' }}>ورشة عمل</option>
                            <option value="competition" {{ request('type') == 'competition' ? 'selected' : '' }}>مسابقة</option>
                            <option value="volunteer" {{ request('type') == 'volunteer' ? 'selected' : '' }}>تطوع</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-navy mb-2">
                            <i class="fas fa-calendar text-gold ml-1"></i> الفترة
                        </label>
                        <select name="period" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                            <option value="">جميع الفترات</option>
                            <option value="upcoming" {{ request('period') == 'upcoming' ? 'selected' : '' }}>قادمة</option>
                            <option value="past" {{ request('period') == 'past' ? 'selected' : '' }}>سابقة</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-navy text-white px-4 py-2 rounded-lg font-bold hover:bg-navy-light transition flex items-center justify-center gap-2">
                            <i class="fas fa-search"></i>
                            بحث
                        </button>
                        <a href="{{ route('staff.activities.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded-lg font-bold hover:bg-gray-500 transition flex items-center justify-center gap-2">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- الجدول -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-right font-bold text-navy">#</th>
                            <th class="px-4 py-3 text-right font-bold text-navy">النشاط</th>
                            <th class="px-4 py-3 text-right font-bold text-navy">النوع</th>
                            <th class="px-4 py-3 text-right font-bold text-navy">التاريخ</th>
                            <th class="px-4 py-3 text-right font-bold text-navy">المكان</th>
                            <th class="px-4 py-3 text-right font-bold text-navy">المسجلين</th>
                            <th class="px-4 py-3 text-right font-bold text-navy">الحالة</th>
                            <th class="px-4 py-3 text-center font-bold text-navy">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities ?? [] as $index => $activity)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="px-4 py-4 font-bold text-gray-600">{{ $index + 1 }}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-navy rounded-full flex items-center justify-center text-white">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-navy">{{ $activity->title ?? 'غير محدد' }}</div>
                                        <div class="text-xs text-gray-500">
                                            <i class="fas fa-map-marker-alt ml-1"></i> {{ $activity->location ?? 'غير محدد' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-bold">
                                    {{ $activity->type->name ?? 'عام' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-gray-600">
                                {{ $activity->date ? \Carbon\Carbon::parse($activity->date)->format('Y/m/d') : 'غير محدد' }}
                            </td>
                            <td class="px-4 py-4 text-gray-600">{{ $activity->location ?? 'غير محدد' }}</td>
                            <td class="px-4 py-4">
                                <span class="bg-gold/20 text-gold px-3 py-1 rounded-full text-sm font-bold">
                                    {{ $activity->registrations_count ?? 0 }} / {{ $activity->max_participants ?? '∞' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                @if($activity->status == 'مفتوح')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-bold">
                                        <i class="fas fa-check-circle ml-1"></i> مفتوح
                                    </span>
                                @elseif($activity->status == 'مغلق')
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-bold">
                                        <i class="fas fa-times-circle ml-1"></i> مغلق
                                    </span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-bold">
                                        <i class="fas fa-clock ml-1"></i> {{ $activity->status ?? 'مسودة' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- زر العرض -->
                                    <a href="{{ route('staff.activities.show', $activity->id) }}" 
                                       class="inline-flex items-center justify-center w-9 h-9 text-blue-600 hover:text-white bg-blue-50 hover:bg-blue-600 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md" 
                                       title="عرض">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    
                                    <!-- زر التعديل -->
                                    <a href="{{ route('staff.activities.edit', $activity->id) }}" 
                                       class="inline-flex items-center justify-center w-9 h-9 text-yellow-600 hover:text-white bg-yellow-50 hover:bg-yellow-600 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md" 
                                       title="تعديل">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    
                                    <!-- زر الحذف -->
                                    <form action="{{ route('staff.activities.destroy', $activity->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('⚠️ هل أنت متأكد من حذف هذا النشاط؟');"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center w-9 h-9 text-red-600 hover:text-white bg-red-50 hover:bg-red-600 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md" 
                                                title="حذف">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-gray-400">
                                <i class="fas fa-inbox text-5xl mb-3"></i>
                                <p class="text-lg font-bold">لا توجد أنشطة مسجلة حالياً</p>
                                <a href="{{ route('staff.activities.create') }}" class="text-gold hover:text-yellow-700 font-bold mt-2 inline-block">
                                    <i class="fas fa-plus ml-1"></i> أضف نشاط جديد
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($activities && $activities->hasPages())
                <div class="mt-6">
                    {{ $activities->links() }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>