<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة نشاط جديد</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #f1f5f9; }
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
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
        }
        .nav-item {
            transition: all 0.3s;
            cursor: pointer;
        }
        .nav-item:hover {
            background: #e0e7ff;
            transform: translateX(-5px);
        }
        .nav-item.active {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(99,102,241,0.3);
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
                <a href="{{ route('activities.index') }}" class="text-gray-400 hover:text-indigo-600 transition">
                    <i class="fas fa-arrow-right text-xl"></i>
                </a>
                <div>
                    <h1 class="font-bold text-2xl text-indigo-700">
                        <i class="fas fa-plus-circle text-indigo-500 ml-2"></i>
                        إضافة نشاط جديد
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">املأ البيانات التالية لإضافة نشاط جديد للنظام</p>
                </div>
            </div>
            <span class="text-gray-600 bg-indigo-50 px-5 py-2 rounded-full text-sm font-semibold border border-indigo-100">
                <i class="fas fa-calendar-alt ml-2 text-indigo-600"></i> {{ now()->format('Y/m/d') }}
            </span>
        </header>

        <!-- Content Area - مع Sidebar -->
        <div class="flex-1 flex overflow-hidden">
            
            <!-- Main Form Content -->
            <main class="flex-1 overflow-y-auto p-8">
                <form method="POST" action="{{ route('activities.store') }}" enctype="multipart/form-data" id="mainForm">
                    @csrf

                    <!-- ==================== القسم 1: المعلومات الأساسية ==================== -->
                    <div id="section-basic" class="section-content active">
                        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-8">
                            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-info-circle text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">المعلومات الأساسية</h2>
                                    <p class="text-sm text-gray-500">البيانات الأساسية للنشاط</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <!-- Title -->
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">
                                        <i class="fas fa-heading text-indigo-600 ml-2"></i>
                                        عنوان النشاط <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="title" value="{{ old('title') }}" 
                                        placeholder="اكتب اسم النشاط"
                                        class="input-field {{ $errors->has('title') ? 'border-red-500' : '' }}">
                                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">
                                        <i class="fas fa-layer-group text-indigo-600 ml-2"></i>
                                        نوع النشاط <span class="text-red-500">*</span>
                                    </label>
                                    
                                    <div class="relative">
                                        <select name="activity_type_id" id="activity_type_select" 
                                            onchange="toggleDynamicFields()"
                                            class="input-field w-full appearance-none pr-10 {{ $errors->has('activity_type_id') ? 'border-red-500' : '' }}"
                                            required>
                                            
                                            <!-- ✅ الخيار الافتراضي -->
                                            <option value="" disabled selected>اختر نوع النشاط</option>
                                            
                                            <!-- ✅ الأنواع من قاعدة البيانات -->
                                            @foreach(\App\Models\ActivityType::all() as $type)
                                                <option value="{{ $type->id }}" {{ old('activity_type_id') == $type->id ? 'selected' : '' }}>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        
                                        <!-- سهم القائمة (يمين لأن الموقع RTL) -->
                                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400"></i>
                                        </div>
                                    </div>
                                    
                                    @error('activity_type_id') 
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                                    @enderror
                                </div>

                                <!-- Supervisor -->
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">
                                        <i class="fas fa-user-tie text-indigo-600 ml-2"></i>
                                        المشرف المسؤول <span class="text-red-500">*</span>
                                    </label>
                                    <select name="created_by" class="input-field {{ $errors->has('created_by') ? 'border-red-500' : '' }}" required>
                                        <option value=""> اختر المشرف </option>
                                        @foreach($supervisors as $supervisor)
                                            <option value="{{ $supervisor->id }}" {{ old('created_by') == $supervisor->id ? 'selected' : '' }}>
                                                {{ $supervisor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('created_by') 
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}

                                    </p>
                                     @enderror
                                </div>

                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">
                                        <i class="fas fa-toggle-on text-indigo-600 ml-2"></i>
                                        حالة النشاط <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex gap-4">
                                        <label class="flex-1 cursor-pointer">
                                            <input type="radio" name="status" value="مفتوح" {{ old('status') === 'مفتوح' ? 'checked' : '' }} 
                                                class="peer sr-only" onchange="updateStatusLabel(this)">
                                            <div class="text-center py-4 border-2 border-gray-200 rounded-xl peer-checked:border-green-500 peer-checked:bg-green-50 transition hover:bg-gray-50">
                                                <i class="fas fa-check-circle text-2xl text-green-500 mb-2"></i>
                                                <div class="font-bold text-gray-700 peer-checked:text-green-700">مفتوح</div>
                                            </div>
                                        </label>
                        
                                    </div>
                                </div>

                                <!-- Description -->
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">
                                        <i class="fas fa-align-right text-indigo-600 ml-2"></i>
                                        وصف النشاط <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="description" rows="5" 
                                        placeholder="اكتب وصفاً شاملاً للنشاط..."
                                        class="input-field {{ $errors->has('description') ? 'border-red-500' : '' }}">{{ old('description') }}</textarea>
                                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== القسم 2: التفاصيل الخاصة (ديناميكي) ==================== -->
                    <div id="section-dynamic" class="section-content">
                        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-8">
                            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-sliders-h text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">التفاصيل الخاصة بالنشاط</h2>
                                    <p class="text-sm text-gray-500">حقول إضافية حسب نوع النشاط</p>
                                </div>
                            </div>

                            <div id="dynamic-fields-container" class="hidden">
                                <!-- 1. الأنشطة التقنية والأكاديمية (ID: 1) -->
                                <div id="type-1-fields" class="dynamic-section space-y-6">
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border-2 border-blue-200">
                                        <h3 class="text-lg font-bold text-blue-700 mb-4 flex items-center gap-2">
                                            <i class="fas fa-laptop-code"></i>
                                            تفاصيل النشاط التقني والأكاديمي
                                        </h3>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-gray-700 font-semibold mb-2">لغة البرمجة المستخدمة</label>
                                                <select name="programming_language" class="input-field">
                                                    <option value="">اختر اللغة...</option>
                                                    <option value="python">Python</option>
                                                    <option value="javascript">JavaScript</option>
                                                    <option value="java">Java</option>
                                                    <option value="cpp">C++</option>
                                                    <option value="csharp">C#</option>
                                                    <option value="php">PHP</option>
                                                    <option value="other">أخرى</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 font-semibold mb-2">المستوى المطلوب</label>
                                                <select name="skill_level" class="input-field">
                                                    <option value="beginner">مبتدئ</option>
                                                    <option value="intermediate">متوسط</option>
                                                    <option value="advanced">متقدم</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <label class="block text-gray-700 font-semibold mb-2">المتطلبات التقنية</label>
                                            <textarea name="technical_requirements" rows="3" placeholder="مثال: تثبيت Python 3.8+، معرفة أساسية بـ..." class="input-field"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2. الأنشطة المهنية والتطويرية (ID: 2) -->
                                <div id="type-2-fields" class="dynamic-section space-y-6">
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border-2 border-green-200">
                                        <h3 class="text-lg font-bold text-green-700 mb-4 flex items-center gap-2">
                                            <i class="fas fa-briefcase"></i>
                                            تفاصيل النشاط المهني والتطويري
                                        </h3>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-gray-700 font-semibold mb-2">الشركات المشاركة</label>
                                                <input type="text" name="participating_companies" placeholder="مثال: Google, Microsoft..." class="input-field">
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 font-semibold mb-2">عدد الوظائف المتاحة</label>
                                                <input type="number" name="available_jobs" placeholder="0" class="input-field">
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <label class="block text-gray-700 font-semibold mb-2">المهارات المستهدفة</label>
                                            <textarea name="targeted_skills" rows="3" placeholder="المهارات التي سيتم تطويرها..." class="input-field"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- 3. الأنشطة الترفيهية والاجتماعية (ID: 3) -->
                                <div id="type-3-fields" class="dynamic-section space-y-6">
                                    <div class="bg-gradient-to-r from-pink-50 to-rose-50 rounded-2xl p-6 border-2 border-pink-200">
                                        <h3 class="text-lg font-bold text-pink-700 mb-4 flex items-center gap-2">
                                            <i class="fas fa-smile-beam"></i>
                                            تفاصيل النشاط الترفيهي والاجتماعي
                                        </h3>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-gray-700 font-semibold mb-2">نوع النشاط</label>
                                                <select name="entertainment_type" class="input-field">
                                                    <option value="">اختر النوع...</option>
                                                    <option value="trip">رحلة</option>
                                                    <option value="party">حفلة</option>
                                                    <option value="games">ألعاب</option>
                                                    <option value="social">اجتماعي</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 font-semibold mb-2">الفئة العمرية</label>
                                                <input type="text" name="age_group" placeholder="مثال: 18-25 سنة" class="input-field">
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <label class="block text-gray-700 font-semibold mb-2">الأنشطة المخططة</label>
                                            <textarea name="planned_activities" rows="3" placeholder="وصف الأنشطة والفعاليات..." class="input-field"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- 4. الهكاثونات والمسابقات المفتوحة (ID: 4) -->
                                <div id="type-4-fields" class="dynamic-section space-y-6">
                                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-2xl p-6 border-2 border-purple-200">
                                        <h3 class="text-lg font-bold text-purple-700 mb-4 flex items-center gap-2">
                                            <i class="fas fa-trophy"></i>
                                            تفاصيل الهاكاثون أو المسابقة
                                        </h3>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                            <div>
                                                <label class="block text-gray-700 font-semibold mb-2">مدة المسابقة (بالساعات)</label>
                                                <input type="number" name="competition_duration" placeholder="24" class="input-field">
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 font-semibold mb-2">حجم الفريق</label>
                                                <input type="number" name="team_size" placeholder="عدد الأعضاء" class="input-field">
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 font-semibold mb-2">قيمة الجوائز</label>
                                                <input type="text" name="prize_value" placeholder="مثال: 3000 دينار " class="input-field">
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <label class="block text-gray-700 font-semibold mb-2">موضوع المسابقة</label>
                                            <textarea name="competition_topic" rows="3" placeholder="المشكلة أو التحدي المطلوب حله..." class="input-field"></textarea>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <label class="block text-gray-700 font-semibold mb-2">معايير التحكيم</label>
                                            <textarea name="judging_criteria" rows="3" placeholder="كيف سيتم تقييم المشاريع..." class="input-field"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- 5. حاضنات الأعمال والابتكار (ID: 5) -->
                                <div id="type-5-fields" class="dynamic-section space-y-6">
                                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-6 border-2 border-yellow-200">
                                        <h3 class="text-lg font-bold text-yellow-700 mb-4 flex items-center gap-2">
                                            <i class="fas fa-lightbulb"></i>
                                            تفاصيل حاضنة الأعمال والابتكار
                                        </h3>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-gray-700 font-semibold mb-2">قطاع الأعمال</label>
                                                <select name="business_sector" class="input-field">
                                                    <option value="">اختر القطاع...</option>
                                                    <option value="tech">تقنية</option>
                                                    <option value="health">صحة</option>
                                                    <option value="education">تعليم</option>
                                                    <option value="finance">مالية</option>
                                                    <option value="other">أخرى</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 font-semibold mb-2">مرحلة المشروع</label>
                                                <select name="project_stage" class="input-field">
                                                    <option value="idea">فكرة</option>
                                                    <option value="prototype">نموذج أولي</option>
                                                    <option value="mvp">منتج أولي</option>
                                                    <option value="growth">نمو</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <label class="block text-gray-700 font-semibold mb-2">فكرة المشروع</label>
                                            <textarea name="project_idea" rows="3" placeholder="وصف مختصر لفكرة المشروع..." class="input-field"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- 6. الأنشطة الرياضية والصحية (ID: 6) -->
                                <div id="type-6-fields" class="dynamic-section space-y-6">
                                    <div class="bg-gradient-to-r from-red-50 to-rose-50 rounded-2xl p-6 border-2 border-red-200">
                                        <h3 class="text-lg font-bold text-red-700 mb-4 flex items-center gap-2">
                                            <i class="fas fa-running"></i>
                                            تفاصيل النشاط الرياضي والصحي
                                        </h3>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-gray-700 font-semibold mb-2">نوع الرياضة</label>
                                                <select name="sport_type" class="input-field">
                                                    <option value="">اختر الرياضة...</option>
                                                    <option value="football">كرة القدم</option>
                                                    <option value="basketball">كرة السلة</option>
                                                    <option value="volleyball">كرة الطائرة</option>
                                                    <option value="fitness">لياقة بدنية</option>
                                                    <option value="other">أخرى</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 font-semibold mb-2">المستوى الرياضي</label>
                                                <select name="fitness_level" class="input-field">
                                                    <option value="beginner">مبتدئ</option>
                                                    <option value="intermediate">متوسط</option>
                                                    <option value="advanced">متقدم</option>
                                                    <option value="professional">محترف</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <label class="block text-gray-700 font-semibold mb-2">المعدات المطلوبة</label>
                                            <textarea name="required_equipment" rows="3" placeholder="الأدوات والمعدات اللازمة..." class="input-field"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- 7. الأنشطة الثقافية والفنية (ID: 7) -->
                                <div id="type-7-fields" class="dynamic-section space-y-6">
                                    <div class="bg-gradient-to-r from-teal-50 to-cyan-50 rounded-2xl p-6 border-2 border-teal-200">
                                        <h3 class="text-lg font-bold text-teal-700 mb-4 flex items-center gap-2">
                                            <i class="fas fa-palette"></i>
                                            تفاصيل النشاط الثقافي والفني
                                        </h3>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-gray-700 font-semibold mb-2">نوع النشاط الفني</label>
                                                <select name="art_type" class="input-field">
                                                    <option value="">اختر النوع...</option>
                                                    <option value="painting">رسم</option>
                                                    <option value="photography">تصوير</option>
                                                    <option value="music">موسيقى</option>
                                                    <option value="theater">مسرح</option>
                                                    <option value="writing">كتابة</option>
                                                    <option value="other">أخرى</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 font-semibold mb-2">الموضوع الثقافي</label>
                                                <input type="text" name="cultural_theme" placeholder="مثال: التراث الشعبي..." class="input-field">
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <label class="block text-gray-700 font-semibold mb-2">المواد الفنية المطلوبة</label>
                                            <textarea name="art_materials" rows="3" placeholder="الأدوات والمواد اللازمة..." class="input-field"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- 8. الإرشاد والتوجيه الأكاديمي (ID: 8) -->
                                <div id="type-8-fields" class="dynamic-section space-y-6">
                                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-2xl p-6 border-2 border-indigo-200">
                                        <h3 class="text-lg font-bold text-indigo-700 mb-4 flex items-center gap-2">
                                            <i class="fas fa-graduation-cap"></i>
                                            تفاصيل الإرشاد والتوجيه الأكاديمي
                                        </h3>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-gray-700 font-semibold mb-2">التخصص الأكاديمي</label>
                                                <select name="academic_specialization" class="input-field">
                                                    <option value="">اختر التخصص...</option>
                                                    <option value="cs">علوم الحاسب</option>
                                                    <option value="is">نظم المعلومات</option>
                                                    <option value="se">هندسة البرمجيات</option>
                                                    <option value="ai">الذكاء الاصطناعي</option>
                                                    <option value="cyber">الأمن السيبراني</option>
                                                    <option value="general">عام</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 font-semibold mb-2">المستوى الدراسي</label>
                                                <select name="academic_level" class="input-field">
                                                    <option value="freshman">سنة أولى</option>
                                                    <option value="sophomore">سنة ثانية</option>
                                                    <option value="junior">سنة ثالثة</option>
                                                    <option value="senior">سنة رابعة</option>
                                                    <option value="graduate">دراسات عليا</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <label class="block text-gray-700 font-semibold mb-2">المواضيع المطروحة</label>
                                            <textarea name="guidance_topics" rows="3" placeholder="المواضيع التي سيتم مناقشتها..." class="input-field"></textarea>
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
                                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-orange-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-map-marker-alt text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">الموعد والمكان</h2>
                                    <p class="text-sm text-gray-500">تفاصيل الوقت والموقع</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">
                                        <i class="fas fa-calendar text-red-600 ml-2"></i>
                                        تاريخ النشاط <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="date" value="{{ old('date') }}" 
                                        class="input-field {{ $errors->has('date') ? 'border-red-500' : '' }}">
                                    @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">
                                        <i class="fas fa-users text-red-600 ml-2"></i>
                                        الحد الأقصى للمشاركين
                                    </label>
                                    <input type="number" name="max_participants" value="{{ old('max_participants') }}" 
                                        placeholder="اتركه فارغاً لعدم التحديد" min="1" class="input-field">
                                    @error('max_participants') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">
                                        <i class="fas fa-clock text-red-600 ml-2"></i>
                                        وقت البداية
                                    </label>
                                    <input type="time" name="time" value="{{ old('time') }}" class="input-field">
                                    @error('time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">
                                        <i class="fas fa-clock text-red-600 ml-2"></i>
                                        وقت الانتهاء
                                    </label>
                                    <input type="time" name="end_time" value="{{ old('end_time') }}" class="input-field">
                                    @error('end_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-gray-700 font-bold mb-2">
                                        <i class="fas fa-map-pin text-red-600 ml-2"></i>
                                        مكان النشاط
                                    </label>
                                    <input type="text" name="location" value="{{ old('location') }}" 
                                        placeholder="مثال: قاعة المؤتمرات - المبنى الرئيسي" class="input-field">
                                    @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-gray-700 font-bold mb-2">
                                        <i class="fas fa-video text-red-600 ml-2"></i>
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
                                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-star text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">النقاط والشهادة</h2>
                                    <p class="text-sm text-gray-500">المكافآت والشهادات</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">
                                        <i class="fas fa-coins text-yellow-600 ml-2"></i>
                                        النقاط المكتسبة
                                    </label>
                                    <input type="number" name="points" value="{{ old('points', 0) }}" 
                                        min="0" placeholder="0" class="input-field">
                                    @error('points') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="flex items-center">
                                    <label class="flex items-center gap-3 cursor-pointer p-4 border-2 border-gray-200 rounded-xl hover:border-yellow-500 transition w-full">
                                        <input type="hidden" name="certificate" value="0">
                                        <input type="checkbox" name="certificate" value="1" 
                                            {{ old('certificate') ? 'checked' : '' }} 
                                            class="w-6 h-6 text-yellow-600 rounded focus:ring-yellow-500">
                                        <div class="flex-1">
                                            <div class="font-bold text-gray-800">منح شهادة حضور</div>
                                            <div class="text-sm text-gray-500">توفير شهادة للمشاركين</div>
                                        </div>
                                        <i class="fas fa-certificate text-3xl text-blue-500"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== القسم 5: الصورة ==================== -->
                    <div id="section-image" class="section-content">
                        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-8">
                            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-image text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">صورة النشاط</h2>
                                    <p class="text-sm text-gray-500">صورة تعبيرية للنشاط</p>
                                </div>
                            </div>

                            <div x-data="imageUpload()" 
                                class="border-3 border-dashed border-gray-300 rounded-2xl p-10 text-center hover:border-indigo-500 transition cursor-pointer bg-gray-50"
                                @click="$refs.fileInput.click()"
                                @dragover.prevent="dragging = true"
                                @dragleave.prevent="dragging = false"
                                @drop.prevent="handleDrop($event)"
                                :class="dragging ? 'border-indigo-500 bg-indigo-50' : ''">
                                
                                <input type="file" name="image" accept="image/*" class="hidden" x-ref="fileInput" @change="handleFile($event)">
                                
                                <template x-if="!preview">
                                    <div>
                                        <i class="fas fa-cloud-upload-alt text-6xl text-gray-300 mb-4"></i>
                                        <p class="text-gray-600 font-bold text-lg mb-2">اسحب الصورة هنا أو اضغط للاختيار</p>
                                        <p class="text-gray-400 text-sm">PNG, JPG, WEBP — حتى 2MB</p>
                                    </div>
                                </template>

                                <template x-if="preview">
                                    <div>
                                        <img :src="preview" class="mx-auto max-h-64 rounded-2xl object-cover mb-4 shadow-lg">
                                        <p class="text-indigo-600 font-bold" x-text="fileName"></p>
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
                            class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold hover:from-indigo-700 hover:to-purple-700 transition shadow-lg">
                            التالي <i class="fas fa-arrow-left mr-2"></i>
                        </button>
                        <button type="submit" id="submitBtn" class="hidden px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-bold hover:from-green-700 hover:to-emerald-700 transition shadow-lg">
                            <i class="fas fa-check ml-2"></i> إضافة النشاط
                        </button>
                    </div>
                </form>
            </main>

            <!-- ✅ Sidebar Navigation - تم إرجاعه -->
            <aside class="w-80 bg-white border-l border-gray-200 p-6 overflow-y-auto hidden md:block">
                <div class="sidebar-nav">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-list-ul text-indigo-600"></i>
                        أقسام النموذج
                    </h3>

                    <nav class="space-y-3">
                        <div onclick="showSection('basic')" id="nav-basic" class="nav-item active flex items-center gap-3 p-4 rounded-xl cursor-pointer">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold">المعلومات الأساسية</div>
                                <div class="text-xs text-gray-500">العنوان، النوع، الوصف</div>
                            </div>
                            <i class="fas fa-check-circle text-green-500 opacity-0" id="check-basic"></i>
                        </div>

                        <div onclick="showSection('dynamic')" id="nav-dynamic" class="nav-item flex items-center gap-3 p-4 rounded-xl cursor-pointer opacity-50">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center text-white">
                                <i class="fas fa-sliders-h"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold">التفاصيل الخاصة</div>
                                <div class="text-xs text-gray-500">حسب نوع النشاط</div>
                            </div>
                            <i class="fas fa-check-circle text-green-500 opacity-0" id="check-dynamic"></i>
                        </div>

                        <div onclick="showSection('location')" id="nav-location" class="nav-item flex items-center gap-3 p-4 rounded-xl cursor-pointer opacity-50">
                            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-orange-600 rounded-lg flex items-center justify-center text-white">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold">الموعد والمكان</div>
                                <div class="text-xs text-gray-500">التاريخ، الوقت، الموقع</div>
                            </div>
                            <i class="fas fa-check-circle text-green-500 opacity-0" id="check-location"></i>
                        </div>

                        <div onclick="showSection('points')" id="nav-points" class="nav-item flex items-center gap-3 p-4 rounded-xl cursor-pointer opacity-50">
                            <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-lg flex items-center justify-center text-white">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold">النقاط والشهادة</div>
                                <div class="text-xs text-gray-500">المكافآت</div>
                            </div>
                            <i class="fas fa-check-circle text-green-500 opacity-0" id="check-points"></i>
                        </div>

                        <div onclick="showSection('image')" id="nav-image" class="nav-item flex items-center gap-3 p-4 rounded-xl cursor-pointer opacity-50">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center text-white">
                                <i class="fas fa-image"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold">صورة النشاط</div>
                                <div class="text-xs text-gray-500">الصورة التعريفية</div>
                            </div>
                            <i class="fas fa-check-circle text-green-500 opacity-0" id="check-image"></i>
                        </div>
                    </nav>

                    <!-- Progress Indicator -->
                    <div class="mt-8 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">التقدم</span>
                            <span class="font-bold text-indigo-600" id="progress-text">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div id="progress-bar" class="bg-gradient-to-r from-indigo-600 to-purple-600 h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
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

        // ✅ Dynamic Fields Toggle - يدعم 8 أنواع أنشطة
        function toggleDynamicFields() {
            const typeSelect = document.getElementById('activity_type_select');
            const typeId = typeSelect.value;
            
            // Hide all dynamic sections
            document.querySelectorAll('.dynamic-section').forEach(section => {
                section.style.display = 'none';
            });
            
            if (typeId) {
                // إظهار القسم المناسب حسب الـ ID
                const section = document.getElementById('type-' + typeId + '-fields');
                if (section) {
                    section.style.display = 'block';
                    document.getElementById('dynamic-fields-container').classList.remove('hidden');
                    document.getElementById('no-type-selected').style.display = 'none';
                    
                    // الانتقال التلقائي للقسم
                    showSection('dynamic');
                    document.getElementById('nav-dynamic').classList.remove('opacity-50');
                    
                    setTimeout(() => {
                        section.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }, 100);
                }
            } else {
                document.getElementById('dynamic-fields-container').classList.add('hidden');
                document.getElementById('no-type-selected').style.display = 'block';
            }
        }

        // ✅ دوال إدارة المتحدثين (إضافة/حذف)
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
            
            // تأثير حركي بسيط
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

        function updateStatusLabel(radio) {
            console.log('Status changed to:', radio.value);
        }
    </script>
</body>
</html>