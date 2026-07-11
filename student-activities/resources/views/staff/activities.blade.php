<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل بيانات الطالب - لوحة المشرف</title>
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
                <i class="fas fa-user-edit text-gold ml-2"></i>
                تعديل بيانات الطالب
            </h1>
            <a href="{{ route('staff.students.index') }}" class="bg-gray-200 text-navy px-6 py-3 rounded-xl hover:bg-gray-300 transition font-bold">
                <i class="fas fa-arrow-right ml-2"></i> رجوع
            </a>
        </div>

        <form method="POST" action="{{ route('staff.students.update', $student->id) }}" class="bg-white rounded-2xl shadow-lg p-8">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- الاسم -->
                <div>
                    <label class="block text-navy font-bold mb-2">
                        <i class="fas fa-user text-gold ml-2"></i>
                        الاسم الكامل <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}" 
                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-gold focus:ring-2 focus:ring-gold/20 transition" 
                        required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- البريد الإلكتروني -->
                <div>
                    <label class="block text-navy font-bold mb-2">
                        <i class="fas fa-envelope text-gold ml-2"></i>
                        البريد الإلكتروني <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}" 
                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-gold focus:ring-2 focus:ring-gold/20 transition" 
                        required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- رقم الهاتف -->
                <div>
                    <label class="block text-navy font-bold mb-2">
                        <i class="fas fa-phone text-gold ml-2"></i>
                        رقم الهاتف
                    </label>
                    <input type="tel" name="phone" value="{{ old('phone', $student->phone ?? '') }}" 
                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- رقم الطالب -->
                <div>
                    <label class="block text-navy font-bold mb-2">
                        <i class="fas fa-id-card text-gold ml-2"></i>
                        رقم الطالب
                    </label>
                    <input type="text" name="student_number" value="{{ old('student_number', $student->student_number ?? '') }}" 
                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                    @error('student_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- النقاط -->
                <div>
                    <label class="block text-navy font-bold mb-2">
                        <i class="fas fa-coins text-gold ml-2"></i>
                        النقاط
                    </label>
                    <input type="number" name="total_points" value="{{ old('total_points', $student->total_points ?? 0) }}" 
                        min="0" 
                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                    @error('total_points') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- زر الحفظ -->
                <div class="flex justify-end gap-3">
                    <a href="{{ route('staff.students.index') }}" class="px-8 py-3 border-2 border-gray-300 text-gray-600 rounded-xl font-bold hover:bg-gray-50 transition">
                        إلغاء
                    </a>
                    <button type="submit" class="bg-gold text-navy px-8 py-3 rounded-xl font-bold hover:bg-yellow-600 transition shadow-lg">
                        <i class="fas fa-save ml-2"></i>
                        حفظ التعديلات
                    </button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>