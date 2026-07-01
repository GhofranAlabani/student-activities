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
        .bg-navy-light { background-color: #112240; }
        .text-navy { color: #0a1929; }
        .text-gold { color: #d4a017; }
        .bg-gold { background-color: #d4a017; }
        .bg-gold:hover { background-color: #b8860b; }
        .border-gold { border-color: #d4a017; }
        
        .input-field {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            font-family: 'Cairo', sans-serif;
            font-size: 0.95rem;
            transition: all 0.3s;
            background: #fff;
        }
        .input-field:focus {
            outline: none;
            border-color: #d4a017;
            box-shadow: 0 0 0 4px rgba(212,160,23,0.1);
        }
        .nav-item {
            transition: all 0.3s;
            cursor: pointer;
        }
        .nav-item:hover {
            background: rgba(212,160,23,0.1);
            transform: translateX(-5px);
        }
        .nav-item.active {
            background: linear-gradient(135deg, #0a1929 0%, #112240 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(10,25,41,0.3);
        }
        .section-content {
            display: none;
            animation: fadeIn 0.4s ease;
        }
        .section-content.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .sidebar-nav {
            position: sticky;
            top: 20px;
        }
        .speaker-row {
            animation: slideIn 0.3s ease;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        
        <!-- Header -->
        <header class="bg-white shadow-sm p-4 flex justify-between items-center z-10">
            <div class="flex items-center gap-3">
                <a href="{{ route('staff.activities.index') }}" class="text-gray-400 hover:text-gold transition">
                    <i class="fas fa-arrow-right text-xl"></i>
                </a>
                <div>
                    <h1 class="font-bold text-2xl text-navy">
                        <i class="fas fa-plus-circle text-gold ml-2"></i>
                        إضافة نشاط جديد
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">املأ البيانات التالية لإضافة نشاط جديد</p>
                </div>
            </div>
            <span class="text-navy bg-gold/20 px-5 py-2 rounded-full text-sm font-semibold border border-gold/30">
                <i class="fas fa-calendar-alt ml-2 text-gold"></i> {{ now()->format('Y/m/d') }}
            </span>
        </header>

        <!-- Content Area -->
        <div class="flex-1 flex overflow-hidden">
            
            <!-- Main Form Content -->
            <main class="flex-1 overflow-y-auto p-8">
                <form method="POST" action="{{ route('staff.activities.store') }}" enctype="multipart/form-data" id="mainForm">
                    @csrf

                    <!-- ==================== القسم 1: المعلومات الأساسية ==================== -->
                    <div id="section-basic" class="section-content active">
                        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-8">
                            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-navy to-navy-light rounded-xl flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-info-circle text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-navy">المعلومات الأساسية</h2>
                                    <p class="text-sm text-gray-500">البيانات الأساسية للنشاط</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <!-- Title -->
                                <div>
                                    <label class="block text-navy font-bold mb-2">
                                        <i class="fas fa-heading text-gold ml-2"></i>
                                        عنوان النشاط <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="title" value="{{ old('title') }}" 
                                        placeholder="مثال: مؤتمر الذكاء الاصطناعي 2026"
                                        class="input-field {{ $errors->has('title') ? 'border-red-500' : '' }}" required>
                                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <!-- Type -->
                                <div>
                                    <label class="block text-navy font-bold mb-2">
                                        <i class="fas fa-layer-group text-gold ml-2"></i>
                                        نوع النشاط <span class="text-red-500">*</span>
                                    </label>
                                    <select name="type_id" id="activity_type_select" 
                                        onchange="toggleDynamicFields()"
                                        class="input-field {{ $errors->has('type_id') ? 'border-red-500' : '' }}" required>
                                        <option value="">-- اختر نوع النشاط --</option>
                                        @foreach($activityTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <label class="block text-navy font-bold mb-2">
                                        <i class="fas fa-align-right text-gold ml-2"></i>
                                        وصف النشاط <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="description" rows="5" 
                                        placeholder="اكتب وصفاً شاملاً للنشاط..."
                                        class="input-field {{ $errors->has('description') ? 'border-red-500' : '' }}" required>{{ old('description') }}</textarea>
                                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== القسم 2: التفاصيل الخاصة (ديناميكي) ==================== -->
                    <div id="section-dynamic" class="section-content">
                        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-8">
                            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-navy to-navy-light rounded-xl flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-sliders-h text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-navy">التفاصيل الخاصة بالنشاط</h2>
                                    <p class="text-sm text-gray-500">حقول إضافية حسب نوع النشاط</p>
                                </div>
                            </div>

                            <div id="dynamic-fields-container" class="hidden">
                                <!-- Conference Fields (نوع 1) -->
                                <div id="type-1-fields" class="dynamic-section space-y-6">
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border-2 border-blue-200">
                                        <h3 class="text-lg font-bold text-blue-700 mb-4 flex items-center gap-2">
                                            <i class="fas fa-microphone-alt"></i>
                                            تفاصيل المؤتمر
                                        </h3>
                                        
                                        <div class="bg-white p-5 rounded-xl border border-blue-100 shadow-sm mb-6">
                                            <label class="block text-navy font-bold mb-3 text-lg">
                                                <i class="fas fa-user-tie text-gold ml-2"></i> المتحدثون
                                                <span class="text-xs text-gray-400 font-normal mr-2">(اضغط + لإضافة متحدثين آخرين)</span>
                                            </label>

                                            <div id="speakers-container">
                                                <div class="speaker-row flex gap-3 mb-3 items-center">
                                                    <input type="text" name="speakers[]" placeholder="اسم المتحدث الأول" 
                                                        class="input-field flex-1" value="{{ old('speakers.0') }}">
                                                    <div class="w-10"></div>
                                                </div>
                                                @php $oldSpeakers = old('speakers', []); @endphp
                                                @for($i = 1; $i < count($oldSpeakers); $i++)
                                                    <div class="speaker-row flex gap-3 mb-3 items-center">
                                                        <input type="text" name="speakers[]" placeholder="اسم المتحدث..." 
                                                            class="input-field flex-1" value="{{ $oldSpeakers[$i] }}">
                                                        <button type="button" onclick="removeSpeaker(this)" 
                                                            class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition" title="حذف">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                @endfor
                                            </div>

                                            <button type="button" onclick="addSpeaker()" 
                                                class="mt-2 text-gold hover:text-yellow-700 font-bold text-sm flex items-center gap-2 transition bg-gold/10 px-4 py-2 rounded-lg hover:bg-gold/20">
                                                <i class="fas fa-plus-circle"></i> إضافة متحدث آخر
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-navy font-semibold mb-2">سعة القاعة</label>
                                                <input type="number" name="hall_capacity" value="{{ old('hall_capacity') }}" 
                                                    placeholder="عدد المقاعد" class="input-field">
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <label class="block text-navy font-semibold mb-2">أجندة المؤتمر</label>
                                            <textarea name="agenda" rows="3" placeholder="جدول أعمال المؤتمر..." 
                                                class="input-field">{{ old('agenda') }}</textarea>
                                        </div>
                                        <div class="mt-4">
                                            <label class="block text-navy font-semibold mb-2">رابط البث المباشر</label>
                                            <input type="url" name="live_stream_link" value="{{ old('live_stream_link') }}" 
                                                placeholder="https://..." class="input-field">
                                        </div>
                                    </div>
                                </div>

                                <!-- Competition Fields (نوع 2) -->
                                <div id="type-2-fields" class="dynamic-section space-y-6">
                                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-6 border-2 border-yellow-200">
                                        <h3 class="text-lg font-bold text-yellow-700 mb-4 flex items-center gap-2">
                                            <i class="fas fa-trophy"></i>
                                            تفاصيل المسابقة
                                        </h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-navy font-semibold mb-2">قيمة الجائزة</label>
                                                <input type="text" name="prize_value" value="{{ old('prize_value') }}" 
                                                    placeholder="1000 دينار" class="input-field">
                                            </div>
                                            <div>
                                                <label class="block text-navy font-semibold mb-2">عدد أفراد الفريق</label>
                                                <input type="number" name="team_size" value="{{ old('team_size') }}" 
                                                    placeholder="عدد الأعضاء" class="input-field">
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <label class="block text-navy font-semibold mb-2">معايير التحكيم</label>
                                            <textarea name="judging_criteria" rows="3" placeholder="معايير التقييم..." 
                                                class="input-field">{{ old('judging_criteria') }}</textarea>
                                        </div>
                                        <div class="mt-4">
                                            <label class="block text-navy font-semibold mb-2">آخر موعد للتسليم</label>
                                            <input type="date" name="submission_deadline" value="{{ old('submission_deadline') }}" 
                                                class="input-field">
                                        </div>
                                    </div>
                                </div>

                                <!-- Workshop Fields (نوع 3) -->
                                <div id="type-3-fields" class="dynamic-section space-y-6">
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border-2 border-green-200">
                                        <h3 class="text-lg font-bold text-green-700 mb-4 flex items-center gap-2">
                                            <i class="fas fa-tools"></i>
                                            تفاصيل ورشة العمل
                                        </h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-navy font-semibold mb-2">المدة بالساعات</label>
                                                <input type="number" name="duration_hours" value="{{ old('duration_hours') }}" 
                                                    step="0.5" placeholder="3" class="input-field">
                                            </div>
                                            <div>
                                                <label class="block text-navy font-semibold mb-2">المتطلبات المسبقة</label>
                                                <input type="text" name="prerequisites" value="{{ old('prerequisites') }}" 
                                                    placeholder="معرفة أساسية بـ PHP" class="input-field">
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <label class="block text-navy font-semibold mb-2">المواد المطلوبة</label>
                                            <textarea name="materials_list" rows="3" placeholder="الأدوات والمواد..." 
                                                class="input-field">{{ old('materials_list') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Volunteer Fields (نوع 4) -->
                                <div id="type-4-fields" class="dynamic-section space-y-6">
                                    <div class="bg-gradient-to-r from-pink-50 to-rose-50 rounded-2xl p-6 border-2 border-pink-200">
                                        <h3 class="text-lg font-bold text-pink-700 mb-4 flex items-center gap-2">
                                            <i class="fas fa-hands-helping"></i>
                                            تفاصيل العمل التطوعي
                                        </h3>
                                        <div>
                                            <label class="block text-navy font-semibold mb-2">المهارات المطلوبة</label>
                                            <input type="text" name="required_skills" value="{{ old('required_skills') }}" 
                                                placeholder="التواصل، العمل الجماعي" class="input-field">
                                        </div>
                                        <div class="mt-4">
                                            <label class="block text-navy font-semibold mb-2">وسائل النقل</label>
                                            <input type="text" name="transportation" value="{{ old('transportation') }}" 
                                                placeholder="حافلة متوفرة" class="input-field">
                                        </div>
                                        <div class="mt-4">
                                            <label class="block text-navy font-semibold mb-2">أثر النشاط على المجتمع</label>
                                            <textarea name="community_impact" rows="3" placeholder="الفائدة المجتمعية..." 
                                                class="input-field">{{ old('community_impact') }}</textarea>
                                        </div>
                                        <div class="mt-4 flex items-center gap-3">
                                            <input type="checkbox" name="uniform_provided" id="uniform_prov" value="1" 
                                                {{ old('uniform_provided') ? 'checked' : '' }} 
                                                class="w-5 h-5 text-gold rounded focus:ring-gold">
                                            <label for="uniform_prov" class="text-navy font-semibold cursor-pointer">
                                                👕 توفير زي موحد
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="no-type-selected" class="text-center py-12 text-gray-400">
                                <i class="fas fa-hand-pointer text-6xl mb-4"></i>
                                <p class="text-lg">الرجاء اختيار نوع النشاط من القسم الأول لعرض الحقول الخاصة</p>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== القسم 3: الموعد والمكان ==================== -->
                    <div id="section-location" class="section-content">
                        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-8">
                            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-navy to-navy-light rounded-xl flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-map-marker-alt text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-navy">الموعد والمكان</h2>
                                    <p class="text-sm text-gray-500">تفاصيل الوقت والموقع</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-navy font-bold mb-2">
                                        <i class="fas fa-calendar text-gold ml-2"></i>
                                        تاريخ النشاط <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="date" value="{{ old('date') }}" 
                                        class="input-field {{ $errors->has('date') ? 'border-red-500' : '' }}" required>
                                    @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-navy font-bold mb-2">
                                        <i class="fas fa-users text-gold ml-2"></i>
                                        الحد الأقصى للمشاركين
                                    </label>
                                    <input type="number" name="max_participants" value="{{ old('max_participants') }}" 
                                        placeholder="اتركه فارغاً لعدم التحديد" min="1" class="input-field">
                                    @error('max_participants') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-navy font-bold mb-2">
                                        <i class="fas fa-clock text-gold ml-2"></i>
                                        وقت البداية
                                    </label>
                                    <input type="time" name="time" value="{{ old('time') }}" class="input-field">
                                    @error('time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-navy font-bold mb-2">
                                        <i class="fas fa-clock text-gold ml-2"></i>
                                        وقت الانتهاء
                                    </label>
                                    <input type="time" name="end_time" value="{{ old('end_time') }}" class="input-field">
                                    @error('end_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-navy font-bold mb-2">
                                        <i class="fas fa-map-pin text-gold ml-2"></i>
                                        مكان النشاط <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="location" value="{{ old('location') }}" 
                                        placeholder="مثال: قاعة المؤتمرات - المبنى الرئيسي" 
                                        class="input-field {{ $errors->has('location') ? 'border-red-500' : '' }}" required>
                                    @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-navy font-bold mb-2">
                                        <i class="fas fa-video text-gold ml-2"></i>
                                        رابط الحضور أونلاين (اختياري)
                                    </label>
                                    <input type="url" name="online_link" value="{{ old('online_link') }}" 
                                        placeholder="https://meet.google.com/..." class="input-field">
                                    @error('online_link') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== القسم 4: النقاط والشهادة ==================== -->
                    <div id="section-points" class="section-content">
                        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-8">
                            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-navy to-navy-light rounded-xl flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-star text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-navy">النقاط والشهادة</h2>
                                    <p class="text-sm text-gray-500">المكافآت والشهادات</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-navy font-bold mb-2">
                                        <i class="fas fa-coins text-gold ml-2"></i>
                                        النقاط المكتسبة
                                    </label>
                                    <input type="number" name="points" value="{{ old('points', 0) }}" 
                                        min="0" placeholder="0" class="input-field">
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
                    </div>

                    <!-- ==================== القسم 5: الصورة ==================== -->
                    <div id="section-image" class="section-content">
                        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-8">
                            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-navy to-navy-light rounded-xl flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-image text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-navy">صورة النشاط</h2>
                                    <p class="text-sm text-gray-500">صورة تعبيرية للنشاط</p>
                                </div>
                            </div>

                            <div x-data="imageUpload()" 
                                class="border-3 border-dashed border-gray-300 rounded-2xl p-10 text-center hover:border-gold transition cursor-pointer bg-gray-50"
                                @click="$refs.fileInput.click()"
                                @dragover.prevent="dragging = true"
                                @dragleave.prevent="dragging = false"
                                @drop.prevent="handleDrop($event)"
                                :class="dragging ? 'border-gold bg-gold/5' : ''">
                                
                                <input type="file" name="image" accept="image/*" class="hidden" x-ref="fileInput" @change="handleFile($event)">
                                
                                <template x-if="!preview">
                                    <div>
                                        <i class="fas fa-cloud-upload-alt text-6xl text-gray-300 mb-4"></i>
                                        <p class="text-navy font-bold text-lg mb-2">اسحب الصورة هنا أو اضغط للاختيار</p>
                                        <p class="text-gray-400 text-sm">PNG, JPG, WEBP — حتى 2MB</p>
                                    </div>
                                </template>

                                <template x-if="preview">
                                    <div>
                                        <img :src="preview" class="mx-auto max-h-64 rounded-2xl object-cover mb-4 shadow-lg">
                                        <p class="text-gold font-bold" x-text="fileName"></p>
                                        <p class="text-gray-400 text-sm mt-1">اضغط لتغيير الصورة</p>
                                    </div>
                                </template>
                            </div>
                            @error('image') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-between items-center mt-8 mb-4">
                        <button type="button" onclick="previousSection()" id="prevBtn" 
                            class="px-8 py-3 border-2 border-gray-300 text-gray-600 rounded-xl font-bold hover:bg-gray-50 transition hidden">
                            <i class="fas fa-arrow-right ml-2"></i> السابق
                        </button>
                        <div></div>
                        <button type="button" onclick="nextSection()" id="nextBtn" 
                            class="px-8 py-3 bg-gradient-to-r from-navy to-navy-light text-white rounded-xl font-bold hover:from-navy-light hover:to-navy transition shadow-lg">
                            التالي <i class="fas fa-arrow-left mr-2"></i>
                        </button>
                        <button type="submit" id="submitBtn" class="hidden px-8 py-3 bg-gold text-navy rounded-xl font-bold hover:bg-yellow-600 transition shadow-lg">
                            <i class="fas fa-check ml-2"></i> إضافة النشاط
                        </button>
                    </div>
                </form>
            </main>

            <!-- Sidebar Navigation -->
            <aside class="w-80 bg-white border-l border-gray-200 p-6 overflow-y-auto">
                <div class="sidebar-nav">
                    <h3 class="text-lg font-bold text-navy mb-6 flex items-center gap-2">
                        <i class="fas fa-list-ul text-gold"></i>
                        أقسام النموذج
                    </h3>

                    <nav class="space-y-3">
                        <div onclick="showSection('basic')" id="nav-basic" class="nav-item active flex items-center gap-3 p-4 rounded-xl cursor-pointer">
                            <div class="w-10 h-10 bg-gradient-to-br from-navy to-navy-light rounded-lg flex items-center justify-center text-white">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold">المعلومات الأساسية</div>
                                <div class="text-xs text-gray-500">العنوان، النوع، الوصف</div>
                            </div>
                            <i class="fas fa-check-circle text-gold opacity-0" id="check-basic"></i>
                        </div>

                        <div onclick="showSection('dynamic')" id="nav-dynamic" class="nav-item flex items-center gap-3 p-4 rounded-xl cursor-pointer opacity-50">
                            <div class="w-10 h-10 bg-gradient-to-br from-navy to-navy-light rounded-lg flex items-center justify-center text-white">
                                <i class="fas fa-sliders-h"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold">التفاصيل الخاصة</div>
                                <div class="text-xs text-gray-500">حسب نوع النشاط</div>
                            </div>
                            <i class="fas fa-check-circle text-gold opacity-0" id="check-dynamic"></i>
                        </div>

                        <div onclick="showSection('location')" id="nav-location" class="nav-item flex items-center gap-3 p-4 rounded-xl cursor-pointer opacity-50">
                            <div class="w-10 h-10 bg-gradient-to-br from-navy to-navy-light rounded-lg flex items-center justify-center text-white">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold">الموعد والمكان</div>
                                <div class="text-xs text-gray-500">التاريخ، الوقت، الموقع</div>
                            </div>
                            <i class="fas fa-check-circle text-gold opacity-0" id="check-location"></i>
                        </div>

                        <div onclick="showSection('points')" id="nav-points" class="nav-item flex items-center gap-3 p-4 rounded-xl cursor-pointer opacity-50">
                            <div class="w-10 h-10 bg-gradient-to-br from-navy to-navy-light rounded-lg flex items-center justify-center text-white">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold">النقاط والشهادة</div>
                                <div class="text-xs text-gray-500">المكافآت</div>
                            </div>
                            <i class="fas fa-check-circle text-gold opacity-0" id="check-points"></i>
                        </div>

                        <div onclick="showSection('image')" id="nav-image" class="nav-item flex items-center gap-3 p-4 rounded-xl cursor-pointer opacity-50">
                            <div class="w-10 h-10 bg-gradient-to-br from-navy to-navy-light rounded-lg flex items-center justify-center text-white">
                                <i class="fas fa-image"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold">صورة النشاط</div>
                                <div class="text-xs text-gray-500">الصورة التعريفية</div>
                            </div>
                            <i class="fas fa-check-circle text-gold opacity-0" id="check-image"></i>
                        </div>
                    </nav>

                    <!-- Progress Indicator -->
                    <div class="mt-8 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">التقدم</span>
                            <span class="font-bold text-gold" id="progress-text">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div id="progress-bar" class="bg-gradient-to-r from-navy to-gold h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </aside>

        </div>
    </div>

    <!-- Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        // Alpine.js for image upload
        function imageUpload() {
            return {
                preview: null, fileName: '', dragging: false,
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

        // Section Navigation
        let currentSection = 0;
        const sections = ['basic', 'dynamic', 'location', 'points', 'image'];

        function showSection(sectionName) {
            document.querySelectorAll('.section-content').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById('section-' + sectionName).classList.add('active');
            
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            document.getElementById('nav-' + sectionName).classList.add('active');
            
            currentSection = sections.indexOf(sectionName);
            updateButtons();
            updateProgress();
        }

        function nextSection() {
            if (currentSection < sections.length - 1) {
                document.getElementById('check-' + sections[currentSection]).classList.remove('opacity-0');
                const nextIndex = currentSection + 1;
                document.getElementById('nav-' + sections[nextIndex]).classList.remove('opacity-50');
                showSection(sections[nextIndex]);
            }
        }

        function previousSection() {
            if (currentSection > 0) {
                showSection(sections[currentSection - 1]);
            }
        }

        function updateButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');
            
            prevBtn.classList.toggle('hidden', currentSection === 0);
            
            if (currentSection === sections.length - 1) {
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }
        }

        function updateProgress() {
            const progress = ((currentSection + 1) / sections.length) * 100;
            document.getElementById('progress-bar').style.width = progress + '%';
            document.getElementById('progress-text').textContent = Math.round(progress) + '%';
        }

        // Dynamic Fields Toggle
        function toggleDynamicFields() {
            const typeSelect = document.getElementById('activity_type_select');
            const typeId = typeSelect.value;
            const selectedOption = typeSelect.options[typeSelect.selectedIndex];
            const typeName = selectedOption ? selectedOption.text.toLowerCase().trim() : '';
            
            document.querySelectorAll('.dynamic-section').forEach(section => {
                section.style.display = 'none';
            });
            
            if (typeId) {
                let sectionToShow = null;
                
                if (typeName.includes('مؤتمر') || typeName.includes('conference')) {
                    sectionToShow = document.getElementById('type-1-fields');
                } 
                else if (typeName.includes('مسابقة') || typeName.includes('competition') || typeName.includes('contest')) {
                    sectionToShow = document.getElementById('type-2-fields');
                } 
                else if (typeName.includes('ورشة') || typeName.includes('workshop') || typeName.includes('training')) {
                    sectionToShow = document.getElementById('type-3-fields');
                } 
                else if (typeName.includes('تطوع') || typeName.includes('volunteer') || typeName.includes('voluntary')) {
                    sectionToShow = document.getElementById('type-4-fields');
                } 
                else {
                    sectionToShow = document.getElementById('type-' + typeId + '-fields');
                }
                
                if (sectionToShow) {
                    sectionToShow.style.display = 'block';
                    document.getElementById('dynamic-fields-container').classList.remove('hidden');
                    document.getElementById('no-type-selected').style.display = 'none';
                    
                    showSection('dynamic');
                    document.getElementById('nav-dynamic').classList.remove('opacity-50');
                    
                    setTimeout(() => {
                        sectionToShow.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'nearest' 
                        });
                    }, 100);
                }
            } else {
                document.getElementById('dynamic-fields-container').classList.add('hidden');
                document.getElementById('no-type-selected').style.display = 'block';
            }
        }

        // Speaker Management
        function addSpeaker() {
            const container = document.getElementById('speakers-container');
            const div = document.createElement('div');
            div.className = 'speaker-row flex gap-3 mb-3 items-center';
            
            div.innerHTML = `
                <input type="text" name="speakers[]" placeholder="اسم المتحدث..." class="input-field flex-1">
                <button type="button" onclick="removeSpeaker(this)" 
                    class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition" title="حذف">
                    <i class="fas fa-trash-alt"></i>
                </button>
            `;
            
            container.appendChild(div);
            
            div.style.opacity = '0';
            div.style.transform = 'translateX(20px)';
            setTimeout(() => {
                div.style.transition = 'all 0.3s ease';
                div.style.opacity = '1';
                div.style.transform = 'translateX(0)';
            }, 10);
        }

        function removeSpeaker(btn) {
            const row = btn.parentElement;
            row.style.opacity = '0';
            row.style.transform = 'translateX(-20px)';
            setTimeout(() => row.remove(), 300);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateButtons();
            
            const typeSelect = document.getElementById('activity_type_select');
            if (typeSelect && typeSelect.value) {
                toggleDynamicFields();
                document.getElementById('nav-dynamic').classList.remove('opacity-50');
            }
        });
    </script>
</body>
</html>