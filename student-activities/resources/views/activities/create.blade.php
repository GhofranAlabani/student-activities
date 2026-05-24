<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة نشاط جديد</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #f8fafc; }
        .sidebar-link:hover { background-color: #e0e7ff; color: #4338ca; }
        .sidebar-link.active { background-color: #e0e7ff; color: #4338ca; font-weight: bold; }
        .input-field {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            font-family: 'Cairo', sans-serif;
            font-size: 0.95rem;
            transition: all 0.2s;
            background: #fff;
            color: #1e293b;
        }
        .input-field:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        }
        .input-field.is-invalid { border-color: #ef4444; }
        .label { display: block; font-weight: 600; color: #374151; margin-bottom: 0.4rem; font-size: 0.9rem; }
        .label span.required { color: #ef4444; margin-right: 2px; }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
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
            <a href="{{ route('activities.index') }}" class="sidebar-link active flex items-center p-3 rounded-xl transition duration-200">
                <i class="fas fa-calendar-alt ml-3 text-lg"></i> الأنشطة
            </a>
            <a href="#" class="sidebar-link flex items-center p-3 text-gray-600 rounded-xl transition duration-200">
                <i class="fas fa-users ml-3 text-lg"></i> الطلاب
            </a>
            <a href="#" class="sidebar-link flex items-center p-3 text-gray-600 rounded-xl transition duration-200">
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

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Header -->
        <header class="bg-white shadow-sm p-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="{{ route('activities.index') }}" class="text-gray-400 hover:text-indigo-600 transition">
                    <i class="fas fa-arrow-right text-lg"></i>
                </a>
                <h1 class="font-bold text-xl text-indigo-700">إضافة نشاط جديد</h1>
            </div>
            <span class="text-gray-500 bg-gray-50 px-4 py-2 rounded-full text-sm border border-gray-100">
                <i class="fas fa-calendar-alt ml-1 text-indigo-500"></i> {{ now()->format('Y/m/d') }}
            </span>
        </header>

        <!-- Content -->
        <main class="flex-1 overflow-y-auto p-6 md:p-8">
            <div class="max-w-4xl mx-auto">

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-6 flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5 text-lg"></i>
                        <ul class="text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('activities.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Section: Basic Info -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-4">
                            <i class="fas fa-info-circle text-indigo-500"></i>
                            المعلومات الأساسية
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <!-- Title -->
                            <div class="md:col-span-2">
                                <label class="label">عنوان النشاط <span class="required">*</span></label>
                                <input
                                    type="text"
                                    name="title"
                                    value="{{ old('title') }}"
                                    placeholder="أدخل عنوان النشاط..."
                                    class="input-field {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                >
                                @error('title')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Activity Type -->
                            <div>
                                <label class="label">نوع النشاط <span class="required">*</span></label>
                                <select name="activity_type_id" class="input-field {{ $errors->has('activity_type_id') ? 'is-invalid' : '' }}">
                                    <option value="">-- اختر النوع --</option>
                                    @foreach(\App\Models\ActivityType::all() as $type)
                                        <option value="{{ $type->id }}" {{ old('activity_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('activity_type_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="label">الحالة <span class="required">*</span></label>
                                <select name="status" class="input-field {{ $errors->has('status') ? 'is-invalid' : '' }}">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>متاح للتسجيل</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير متاح</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>منتهي</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label class="label">وصف النشاط <span class="required">*</span></label>
                                <textarea
                                    name="description"
                                    rows="4"
                                    placeholder="اكتب وصفاً تفصيلياً عن النشاط..."
                                    class="input-field {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                >{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Section: Time & Location -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-4">
                            <i class="fas fa-map-marker-alt text-red-400"></i>
                            الموعد والمكان
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <!-- Date -->
                            <div>
                                <label class="label">تاريخ النشاط <span class="required">*</span></label>
                                <input
                                    type="date"
                                    name="date"
                                    value="{{ old('date') }}"
                                    class="input-field {{ $errors->has('date') ? 'is-invalid' : '' }}"
                                >
                                @error('date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Max Participants -->
                            <div>
                                <label class="label">الحد الأقصى للمشاركين</label>
                                <input
                                    type="number"
                                    name="max_participants"
                                    value="{{ old('max_participants') }}"
                                    placeholder="اتركه فارغاً لعدم التحديد"
                                    min="1"
                                    class="input-field {{ $errors->has('max_participants') ? 'is-invalid' : '' }}"
                                >
                                @error('max_participants')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Start Time -->
                            <div>
                                <label class="label">وقت البداية</label>
                                <input
                                    type="time"
                                    name="time"
                                    value="{{ old('time') }}"
                                    class="input-field {{ $errors->has('time') ? 'is-invalid' : '' }}"
                                >
                                @error('time')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Time -->
                            <div>
                                <label class="label">وقت الانتهاء</label>
                                <input
                                    type="time"
                                    name="end_time"
                                    value="{{ old('end_time') }}"
                                    class="input-field {{ $errors->has('end_time') ? 'is-invalid' : '' }}"
                                >
                                @error('end_time')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Location -->
                            <div class="md:col-span-2">
                                <label class="label">مكان النشاط</label>
                                <input
                                    type="text"
                                    name="location"
                                    value="{{ old('location') }}"
                                    placeholder="مثال: قاعة المؤتمرات - المبنى الرئيسي"
                                    class="input-field {{ $errors->has('location') ? 'is-invalid' : '' }}"
                                >
                                @error('location')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Online Link -->
                            <div class="md:col-span-2">
                                <label class="label">رابط الحضور أونلاين (اختياري)</label>
                                <input
                                    type="url"
                                    name="online_link"
                                    value="{{ old('online_link') }}"
                                    placeholder="https://meet.google.com/..."
                                    class="input-field {{ $errors->has('online_link') ? 'is-invalid' : '' }}"
                                >
                                @error('online_link')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Section: Points & Certificate -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-4">
                            <i class="fas fa-star text-yellow-400"></i>
                            النقاط والشهادة
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <!-- Points -->
                            <div>
                                <label class="label">النقاط المكتسبة</label>
                                <input
                                    type="number"
                                    name="points"
                                    value="{{ old('points', 0) }}"
                                    min="0"
                                    placeholder="0"
                                    class="input-field {{ $errors->has('points') ? 'is-invalid' : '' }}"
                                >
                                @error('points')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Certificate -->
                            <div class="flex items-center gap-3 mt-6">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="certificate" value="0">
                                    <input
                                        type="checkbox"
                                        name="certificate"
                                        value="1"
                                        {{ old('certificate') ? 'checked' : '' }}
                                        class="sr-only peer"
                                        id="certificate_toggle"
                                    >
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:right-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                                <label for="certificate_toggle" class="font-semibold text-gray-700 cursor-pointer">
                                    <i class="fas fa-certificate text-blue-500 ml-1"></i>
                                    منح شهادة حضور
                                </label>
                            </div>

                        </div>
                    </div>

                    <!-- Section: Image -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-4">
                            <i class="fas fa-image text-purple-400"></i>
                            صورة النشاط
                        </h2>

                        <div
                            x-data="imageUpload()"
                            class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-indigo-400 transition cursor-pointer"
                            @click="$refs.fileInput.click()"
                            @dragover.prevent="dragging = true"
                            @dragleave.prevent="dragging = false"
                            @drop.prevent="handleDrop($event)"
                            :class="dragging ? 'border-indigo-500 bg-indigo-50' : ''"
                        >
                            <input
                                type="file"
                                name="image"
                                accept="image/*"
                                class="hidden"
                                x-ref="fileInput"
                                @change="handleFile($event)"
                            >

                            <template x-if="!preview">
                                <div>
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-500 font-semibold mb-1">اسحب الصورة هنا أو اضغط للاختيار</p>
                                    <p class="text-gray-400 text-sm">PNG, JPG, WEBP — حتى 2MB</p>
                                </div>
                            </template>

                            <template x-if="preview">
                                <div>
                                    <img :src="preview" class="mx-auto max-h-48 rounded-xl object-cover mb-3">
                                    <p class="text-indigo-600 text-sm font-semibold" x-text="fileName"></p>
                                    <p class="text-gray-400 text-xs mt-1">اضغط لتغيير الصورة</p>
                                </div>
                            </template>
                        </div>

                        @error('image')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('activities.index') }}" class="px-6 py-3 rounded-xl border-2 border-gray-300 text-gray-600 font-bold hover:bg-gray-50 transition">
                            <i class="fas fa-times ml-2"></i> إلغاء
                        </a>
                        <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg hover:shadow-indigo-300 transform hover:-translate-y-0.5">
                            <i class="fas fa-plus ml-2"></i> إضافة النشاط
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function imageUpload() {
            return {
                preview: null,
                fileName: '',
                dragging: false,
                handleFile(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.fileName = file.name;
                        const reader = new FileReader();
                        reader.onload = (e) => { this.preview = e.target.result; };
                        reader.readAsDataURL(file);
                    }
                },
                handleDrop(event) {
                    this.dragging = false;
                    const file = event.dataTransfer.files[0];
                    if (file && file.type.startsWith('image/')) {
                        this.$refs.fileInput.files = event.dataTransfer.files;
                        this.fileName = file.name;
                        const reader = new FileReader();
                        reader.onload = (e) => { this.preview = e.target.result; };
                        reader.readAsDataURL(file);
                    }
                }
            }
        }
    </script>
</body>
</html>