<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل النشاط - لوحة المشرف</title>
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
                <a href="{{ route('staff.activities.index') }}" class="text-gray-300 hover:text-gold text-sm">
                    <i class="fas fa-arrow-right ml-1"></i> رجوع للأنشطة
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-8 max-w-4xl">
        
        <h2 class="text-3xl font-black text-navy mb-6">تعديل النشاط: {{ $activity->title }}</h2>

        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form action="{{ route('staff.activities.update', $activity->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- عنوان النشاط -->
                    <div>
                        <label class="block text-navy font-bold mb-2">عنوان النشاط *</label>
                        <input type="text" name="title" value="{{ old('title', $activity->title) }}" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">
                    </div>

                    <!-- الوصف -->
                    <div>
                        <label class="block text-navy font-bold mb-2">الوصف *</label>
                        <textarea name="description" rows="5" required
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">{{ old('description', $activity->description) }}</textarea>
                    </div>

                    <!-- النوع -->
                    <div>
                        <label class="block text-navy font-bold mb-2">نوع النشاط *</label>
                        <select name="type_id" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">
                            <option value="">-- اختر النوع --</option>
                            @foreach($activityTypes as $type)
                                <option value="{{ $type->id }}" {{ old('type_id', $activity->type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- المكان -->
                    <div>
                        <label class="block text-navy font-bold mb-2">المكان *</label>
                        <input type="text" name="location" value="{{ old('location', $activity->location) }}" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gold transition">
                    </div>

                    <!-- التاريخ والوقت -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-navy font-bold mb-2">التاريخ *</label>
                            <input type="date" name="date" value="{{ old('date', $activity->date->format('Y-m-d')) }}" required
                                  