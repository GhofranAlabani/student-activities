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
        body { font-family: 'Cairo', sans-serif; background-color: #f5f0e8; }
        .bg-navy { background-color: #0a1929; }
        .text-navy { color: #0a1929; }
        .text-gold { color: #d4a017; }
        .bg-gold { background-color: #d4a017; }
        .bg-gold:hover { background-color: #b8860b; }
    </style>
</head>
<body>
    <div class="container mx-auto px-6 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-navy">
                <i class="fas fa-plus-circle text-gold ml-2"></i>
                إضافة نشاط جديد
            </h1>
            <a href="{{ route('staff.activities.index') }}" class="bg-gray-200 text-navy px-6 py-3 rounded-xl hover:bg-gray-300 transition font-bold">
                <i class="fas fa-arrow-right ml-2"></i> رجوع
            </a>
        </div>

        <form method="POST" action="{{ route('staff.activities.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- المعلومات الأساسية -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <h2 class="text-xl font-bold text-navy mb-6">
                    <i class="fas fa-info-circle text-gold ml-2"></i>
                    المعلومات الأساسية
                </h2>

                <div class="space-y-6">
                    <div>
                        <label class="block text-navy font-bold mb-2">
                            <i class="fas fa-heading text-gold ml-2"></i>
                            عنوان النشاط <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" 
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-gold focus:ring-2 focus:ring-gold/20 transition" 
                            required>
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-navy font-bold mb-2">
                            <i class="fas fa-layer-group text-gold ml-2"></i>
                            نوع النشاط <span class="text-red-500">*</span>
                        </label>
                        <select name="type_id" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-gold focus:ring-2 focus:ring-gold/20 transition" required>
                            <option value="">-- اختر نوع النشاط --</option>
                            @foreach($activityTypes as $type)
                                <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('type_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-navy font-bold mb-2">
                            <i class="fas fa-align-right text-gold ml-2"></i>
                            وصف النشاط <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" rows="5" 
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-gold focus:ring-2 focus:ring-gold/20 transition" 
                            required>{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- الموعد والمكان -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <h2 class="text-xl font-bold text-navy mb-6">
                    <i class="fas fa-map-marker-alt text-gold ml-2"></i>
                    الموعد والمكان
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-navy font-bold mb-2">
                            <i class="fas fa-calendar text-gold ml-2"></i>
                            تاريخ النشاط <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date" value="{{ old('date') }}" 
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-gold focus:ring-2 focus:ring-gold/20 transition" 
                            required>
                        @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-navy font-bold mb-2">
                            <i class="fas fa-users text-gold ml-2"></i>
                            الحد الأقصى للمشاركين
                        </label>
                        <input type="number" name="max_participants" value="{{ old('max_participants') }}" 
                            placeholder="اتركه فارغاً لعدم التحديد" min="1" 
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                        @error('max_participants') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-navy font-bold mb-2">
                            <i class="fas fa-clock text-gold ml-2"></i>
                            وقت البداية
                        </label>
                        <input type="time" name="time" value="{{ old('time') }}" 
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                        @error('time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-navy font-bold mb-2">
                            <i class="fas fa-clock text-gold ml-2"></i>
                            وقت الانتهاء
                        </label>
                        <input type="time" name="end_time" value="{{ old('end_time') }}" 
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                        @error('end_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-navy font-bold mb-2">
                            <i class="fas fa-map-pin text-gold ml-2"></i>
                            مكان النشاط <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="location" value="{{ old('location') }}" 
                            placeholder="مثال: قاعة المؤتمرات - المبنى الرئيسي" 
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-gold focus:ring-2 focus:ring-gold/20 transition" 
                            required>
                        @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- النقاط والشهادة -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <h2 class="text-xl font-bold text-navy mb-6">
                    <i class="fas fa-star text-gold ml-2"></i>
                    النقاط والشهادة
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-navy font-bold mb-2">
                            <i class="fas fa-coins text-gold ml-2"></i>
                            النقاط المكتسبة
                        </label>
                        <input type="number" name="points" value="{{ old('points', 0) }}" 
                            min="0" placeholder="0" 
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                        @error('points') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center">
                        <label class="flex items-center gap-3 cursor-pointer p-4 border-2 border-gray-200 rounded-xl hover:border-gold transition w-full">
                            <input type="hidden" name="certificate" value="0">
                            <input type="checkbox" name="certificate" value="1" 
                                {{ old('certificate') ? 'checked' : '' }} 
                                class="w-6 h-6 text-gold rounded focus:ring-gold">
                            <div class="flex-1">
                                <div class="font-bold text-navy">منح شهادة حضور</div>
                                <div class="text-sm text-gray-500">توفير شهادة للمشاركين</div>
                            </div>
                            <i class="fas fa-certificate text-3xl text-gold"></i>
                        </label>
                    </div>
                </div>
            </div>

            <!-- الصورة -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <h2 class="text-xl font-bold text-navy mb-6">
                    <i class="fas fa-image text-gold ml-2"></i>
                    صورة النشاط
                </h2>

                <div class="border-2 border-dashed border-gray-300 rounded-2xl p-10 text-center hover:border-gold transition cursor-pointer bg-gray-50">
                    <input type="file" name="image" accept="image/*" class="w-full">
                    <p class="text-gray-400 text-sm mt-2">PNG, JPG, WEBP — حتى 2MB</p>
                </div>
                @error('image') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            <!-- زر الإرسال -->
            <div class="flex justify-end">
                <button type="submit" class="bg-gold text-navy px-8 py-4 rounded-xl font-bold text-lg hover:bg-yellow-600 transition shadow-lg flex items-center gap-2">
                    <i class="fas fa-check"></i>
                    إضافة النشاط
                </button>
            </div>
        </form>
    </div>
</body>
</html>