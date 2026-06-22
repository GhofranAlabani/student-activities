<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة نشاط جديد - لوحة المشرف</title>
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
                <a href="{{ route('staff.activities.index') }}" class="text-gray-300 hover:text-gold text-sm">
                    <i class="fas fa-arrow-right ml-1"></i> رجوع
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-8 max-w-4xl">
        
        <h2 class="text-3xl font-black text-navy mb-6">إضافة نشاط جديد</h2>

        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form action="{{ route('staff.activities.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <!-- عنوان النشاط -->
                    <div>
                        <label class="block text-navy font-bold mb-2">عنوان النشاط *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- الوصف -->
                    <div>
                        <label class="block text-navy font-bold mb-2">الوصف *</label>
                        <textarea name="description" rows="5" required
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- النوع -->
                    <div>
                        <label class="block text-navy font-bold mb-2">نوع النشاط *</label>
                        <select name="type_id" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">
                            <option value="">-- اختر النوع --</option>
                            @foreach($activityTypes as $type)
                                <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('type_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- المكان -->
                    <div>
                        <label class="block text-navy font-bold mb-2">المكان *</label>
                        <input type="text" name="location" value="{{ old('location') }}" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">
                        @error('location')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- التاريخ والوقت -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-navy font-bold mb-2">التاريخ *</label>
                            <input type="date" name="date" value="{{ old('date') }}" required
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">
                            @error('date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-navy font-bold mb-2">الوقت *</label>
                            <input type="time" name="time" value="{{ old('time') }}" required
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">
                            @error('time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- عدد المشاركين والنقاط -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-navy font-bold mb-2">الحد الأقصى للمشاركين</label>
                            <input type="number" name="max_participants" value="{{ old('max_participants') }}" min="1"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">
                            @error('max_participants')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-navy font-bold mb-2">النقاط</label>
                            <input type="number" name="points" value="{{ old('points') }}" min="0"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">
                            @error('points')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- الصورة -->
                    <div>
                        <label class="block text-navy font-bold mb-2">صورة النشاط</label>
                        <input type="file" name="image" accept="image/*"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- زر الإرسال -->
                    <div class="flex gap-4 pt-4">
                        <button type="submit" class="bg-gold text-navy px-8 py-3 rounded-xl font-bold hover:bg-yellow-600 transition">
                            <i class="fas fa-save ml-2"></i> إنشاء النشاط
                        </button>
                        <a href="{{ route('staff.activities.index') }}" class="bg-gray-200 text-gray-700 px-8 py-3 rounded-xl font-bold hover:bg-gray-300 transition">
                            إلغاء
                        </a>
                    </div>
                </div>
            </form>
        </div>

    </div>

</body>
</html>