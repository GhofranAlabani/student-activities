<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - منصة الأنشطة الطلابية</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind Config for custom colors -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            DEFAULT: '#0a1929',
                            light: '#112240',
                            dark: '#020c1b'
                        },
                        gold: {
                            DEFAULT: '#d4a017',
                            light: '#e6b422',
                            dark: '#b8860b'
                        }
                    },
                    fontFamily: {
                        cairo: ['Cairo', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <style>
        body { 
            font-family: 'Cairo', sans-serif; 
        }
        
        .gradient-navy { 
            background: linear-gradient(135deg, #0a1929 0%, #1e3a5f 100%); 
        }
        
        /* تخصيص شريط التمرير */
        ::-webkit-scrollbar { 
            width: 8px; 
        }
        ::-webkit-scrollbar-track { 
            background: #0a1929; 
        }
        ::-webkit-scrollbar-thumb { 
            background: #d4a017; 
            border-radius: 10px; 
        }
        ::-webkit-scrollbar-thumb:hover { 
            background: #b8860b; 
        }
        
        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="gradient-navy min-h-screen flex items-center justify-center p-4 relative overflow-y-auto font-cairo">

    <!-- خلفية زخرفية -->
    <div class="fixed top-20 right-20 w-96 h-96 bg-gold/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="fixed bottom-20 left-20 w-96 h-96 bg-gold/5 rounded-full blur-3xl pointer-events-none"></div>

    <!-- بطاقة تسجيل الدخول -->
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 my-auto relative z-10 border border-gold/20">
        
        <!-- الشعار والعنوان -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gold rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl animate-float">
                <i class="fas fa-graduation-cap text-4xl text-navy"></i>
            </div>
            <h2 class="text-3xl font-black text-navy mb-1">تسجيل الدخول</h2>
            <p class="text-gray-600 text-sm">منصة الأنشطة الطلابية</p>
            <div class="flex items-center justify-center gap-2 mt-3">
                <div class="h-px w-12 bg-gold/30"></div>
                <i class="fas fa-star text-gold text-xs"></i>
                <div class="h-px w-12 bg-gold/30"></div>
            </div>
        </div>

        <!-- عرض الأخطاء -->
        @if ($errors->any())
            <div class="bg-red-50 border-2 border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6" role="alert">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                    <span class="font-bold text-sm">يوجد أخطاء:</span>
                </div>
                <ul class="list-disc list-inside text-sm space-y-1 pr-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- رسالة النجاح -->
        @if (session('status'))
            <div class="bg-green-50 border-2 border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6" role="alert">
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span class="font-bold text-sm">{{ session('status') }}</span>
                </div>
            </div>
        @endif

        <!-- نموذج تسجيل الدخول -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- البريد الإلكتروني -->
            <div>
                <label for="email" class="block text-navy font-bold mb-1.5 text-sm">
                    <i class="fas fa-envelope ml-2 text-gold"></i>
                    البريد الإلكتروني
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="username"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gold/50 focus:border-gold transition bg-gray-50 hover:bg-white text-navy"
                    placeholder="example@university.edu"
                >
                @error('email')
                    <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- كلمة المرور -->
            <div>
                <label for="password" class="block text-navy font-bold mb-1.5 text-sm">
                    <i class="fas fa-lock ml-2 text-gold"></i>
                    كلمة المرور
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gold/50 focus:border-gold transition bg-gray-50 hover:bg-white text-navy pr-10"
                        placeholder="••••••••"
                    >
                    <!-- زر إظهار/إخفاء كلمة المرور -->
                    <button type="button" onclick="togglePassword()" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gold transition">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- تذكرني + نسيت كلمة المرور -->
            <div class="flex items-center justify-between pt-2">
                <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                    <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-gold shadow-sm focus:ring-gold/50 w-4 h-4">
                    <span class="text-sm text-gray-600 font-semibold">تذكرني</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-gold hover:text-gold-dark font-bold transition hover:underline" href="{{ route('password.request') }}">
                        نسيت كلمة المرور؟
                    </a>
                @endif
            </div>

            <!-- زر تسجيل الدخول -->
            <button 
                type="submit" 
                class="w-full bg-gold text-navy font-black py-3.5 px-4 rounded-xl transition duration-300 shadow-lg hover:shadow-2xl hover:bg-gold-dark transform hover:-translate-y-0.5 text-lg mt-4 flex items-center justify-center gap-2"
            >
                <i class="fas fa-sign-in-alt"></i>
                تسجيل الدخول
            </button>
        </form>

        <!-- رابط إنشاء حساب -->
        <div class="text-center pt-4">
            <p class="text-gray-600 text-sm">
                ليس لديك حساب؟ 
                <a href="{{ route('register') }}" class="text-gold hover:text-gold-dark font-bold transition hover:underline">
                    سجل الآن
                </a>
            </p>
        </div>

        <!-- رابط العودة -->
        <div class="text-center mt-6 pt-4 border-t-2 border-gold/10">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-navy text-sm font-semibold transition group">
                <i class="fas fa-arrow-right group-hover:-translate-x-1 transition"></i>
                العودة للصفحة الرئيسية
            </a>
        </div>
    </div>

    <!-- JavaScript للتفاعلات -->
    <script>
        // إظهار/إخفاء كلمة المرور
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // منع إعادة إرسال النموذج عند الضغط على Enter مرتين
        document.querySelector('form')?.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التسجيل...';
            }
        });
    </script>
</body>
</html>