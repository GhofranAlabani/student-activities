<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الشخصي والإعدادات</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .bg-navy { background-color: #0a1929; }
        .text-gold { color: #d4a017; }
        .bg-gold { background-color: #d4a017; }
        .bg-gold:hover { background-color: #b8860b; }
        .border-gold { border-color: #d4a017; }
        
        /* تنسيق موحد للحقول */
        .custom-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            outline: none;
            transition: all 0.3s ease;
            background-color: #f9fafb;
            font-family: 'Cairo', sans-serif;
        }
        .custom-input:focus {
            border-color: #d4a017;
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(212, 160, 23, 0.1);
        }
    </style>
</head>
<body class="bg-[#f5f0e8] min-h-screen flex flex-col">

    <!-- Header متوافق مع التصميم -->
    <header class="bg-white shadow-md border-b-2 border-gold/20 sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-4">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="text-navy hover:text-gold transition font-bold flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-lg border border-gray-200">
                        <i class="fas fa-arrow-right"></i> رجوع للوحة التحكم
                    </a>
                @else
                    <a href="{{ route('student.dashboard') }}" class="text-navy hover:text-gold transition font-bold flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-lg border border-gray-200">
                        <i class="fas fa-arrow-right"></i> رجوع للوحة التحكم
                    </a>
                @endif
                <h1 class="text-xl font-black text-navy border-r-2 border-gold pr-4 mr-2 hidden md:block">الإعدادات والملف الشخصي</h1>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="text-gray-700 bg-gold/10 px-4 py-2 rounded-full text-sm border border-gold/30">
                    <i class="fas fa-calendar-alt ml-1 text-gold"></i> {{ now()->format('Y/m/d') }}
                </span>
                <div class="w-10 h-10 bg-gold rounded-full flex items-center justify-center text-navy font-bold shadow-md">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
        </div>
    </header>

    <!-- المحتوى الرئيسي -->
    <main class="flex-grow container mx-auto px-6 py-10 max-w-4xl">
        
        <!-- رسائل النجاح -->
        @if (session('status') === 'profile-updated')
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm animate-pulse">
                <i class="fas fa-check-circle text-green-500 text-lg"></i>
                <p class="font-semibold">تم تحديث معلومات الملف الشخصي بنجاح!</p>
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm animate-pulse">
                <i class="fas fa-check-circle text-green-500 text-lg"></i>
                <p class="font-semibold">تم تحديث كلمة المرور بنجاح!</p>
            </div>
        @endif

        <!-- 1. قسم المعلومات الشخصية -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border-t-4 border-gold">
            <div class="mb-8 border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-black text-navy mb-2 flex items-center gap-2">
                    <i class="fas fa-user-edit text-gold"></i> معلومات الحساب
                </h2>
                <p class="text-gray-500 text-sm">قم بتحديث اسم المستخدم وعنوان البريد الإلكتروني لحسابك.</p>
            </div>

            <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('patch')

                <div>
                    <label for="name" class="block text-sm font-bold text-navy mb-2">الاسم الكامل</label>
                    <input id="name" name="name" type="text" class="custom-input" value="{{ old('name', auth()->user()->name) }}" required autofocus autocomplete="name" />
                    @error('name')
                        <p class="text-red-500 text-xs mt-2 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-navy mb-2">البريد الإلكتروني</label>
                    <input id="email" name="email" type="email" class="custom-input" value="{{ old('email', auth()->user()->email) }}" required autocomplete="username" />
                    @error('email')
                        <p class="text-red-500 text-xs mt-2 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="bg-navy text-white px-8 py-3 rounded-xl hover:bg-gold hover:text-navy transition-all duration-300 font-bold shadow-lg flex items-center gap-2">
                        <i class="fas fa-save"></i> حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>

        <!-- 2. قسم تحديث كلمة المرور -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border-t-4 border-indigo-500">
            <div class="mb-8 border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-black text-navy mb-2 flex items-center gap-2">
                    <i class="fas fa-shield-alt text-indigo-500"></i> الأمان وكلمة المرور
                </h2>
                <p class="text-gray-500 text-sm">تأكد من استخدام كلمة مرور طويلة ومعقدة لضمان أمان حسابك.</p>
            </div>

            <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('put')

                <div>
                    <label for="current_password" class="block text-sm font-bold text-navy mb-2">كلمة المرور الحالية</label>
                    <input id="current_password" name="current_password" type="password" class="custom-input" autocomplete="current-password" />
                    @error('current_password')
                        <p class="text-red-500 text-xs mt-2 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-bold text-navy mb-2">كلمة المرور الجديدة</label>
                        <input id="password" name="password" type="password" class="custom-input" autocomplete="new-password" />
                        @error('password')
                            <p class="text-red-500 text-xs mt-2 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-navy mb-2">تأكيد كلمة المرور</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="custom-input" autocomplete="new-password" />
                        @error('password_confirmation')
                            <p class="text-red-500 text-xs mt-2 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl hover:bg-indigo-700 transition-all duration-300 font-bold shadow-lg flex items-center gap-2">
                        <i class="fas fa-key"></i> تحديث كلمة المرور
                    </button>
                </div>
            </form>
        </div>

        <!-- 3. قسم حذف الحساب -->
        <div class="bg-white rounded-2xl shadow-lg p-8 border-t-4 border-red-500">
            <div class="mb-8 border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-black text-navy mb-2 flex items-center gap-2">
                    <i class="fas fa-trash-alt text-red-500"></i> منطقة الخطر
                </h2>
                <p class="text-gray-500 text-sm">بمجرد حذف حسابك، سيتم حذف جميع موارده وبياناته نهائياً. يرجى تنزيل أي بيانات ترغب في الاحتفاظ بها قبل المتابعة.</p>
            </div>

            <div class="bg-red-50 border border-red-100 rounded-xl p-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h3 class="font-bold text-red-700 mb-1">هل أنت متأكد من رغبتك في حذف حسابك؟</h3>
                    <p class="text-sm text-red-600">هذا الإجراء لا يمكن التراجع عنه نهائياً.</p>
                </div>
                
                <!-- زر فتح نافذة الحذف -->
                <button x-data="" 
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                    class="bg-red-600 text-white px-6 py-3 rounded-xl hover:bg-red-700 transition font-bold shadow-md flex items-center gap-2 whitespace-nowrap">
                    <i class="fas fa-times-circle"></i> حذف الحساب نهائياً
                </button>
            </div>

            <!-- نافذة تأكيد الحذف (Modal) -->
            <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-white rounded-2xl max-w-md mx-auto my-20 shadow-2xl border border-gray-100 relative">
                    @csrf
                    @method('delete')

                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
                        </div>
                        <h2 class="text-2xl font-black text-navy mb-2">تأكيد حذف الحساب</h2>
                        <p class="text-gray-500 text-sm">للأمان، يرجى إدخال كلمة المرور الخاصة بك لتأكيد رغبتك في حذف حسابك نهائياً.</p>
                    </div>

                    <div class="mb-6">
                        <label for="password" class="sr-only">كلمة المرور</label>
                        <input id="password" name="password" type="password" class="custom-input text-center" placeholder="أدخل كلمة المرور هنا" />
                        @error('userDeletion')
                            <p class="text-red-500 text-xs mt-2 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-center gap-3">
                        <button type="button" x-on:click="$dispatch('close')" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition font-bold">إلغاء</button>
                        <button type="submit" class="px-6 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition font-bold">نعم، احذف حسابي</button>
                    </div>
                    
                    <!-- زر إغلاق خارجي -->
                    <button x-on:click="$dispatch('close')" class="absolute top-4 left-4 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </form>
            </x-modal>
        </div>

    </main>

    <!-- Alpine.js للتعامل مع النافذة المنبثقة -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>