<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - منظومة الأنشطة الطلابية</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .bg-navy { background-color: #0a1929; }
        .text-gold { color: #d4a017; }
        .bg-gold { background-color: #d4a017; }
        .bg-gold:hover { background-color: #b8860b; }
        .gradient-gold { background: linear-gradient(135deg, #d4a017 0%, #f4d03f 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(212, 160, 23, 0.15); }
    </style>
</head>
<body class="bg-[#f5f0e8] min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <!-- Background decorations -->
    <div class="absolute top-20 right-20 w-96 h-96 bg-gold/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 left-20 w-96 h-96 bg-gold/10 rounded-full blur-3xl"></div>
    <div class="absolute top-1/2 left-1/2 w-[600px] h-[600px] bg-gold/5 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>

    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-10 relative z-10 card-hover border border-gold/20">
        
        <!-- الشعار والعنوان -->
        <div class="text-center mb-10">
            <div class="w-24 h-24 gradient-gold rounded-3xl flex items-center justify-center mx-auto mb-5 shadow-xl transform rotate-3 hover:rotate-0 transition">
                <i class="fas fa-graduation-cap text-5xl text-white"></i>
            </div>
            <h2 class="text-4xl font-black text-navy mb-2">تسجيل الدخول</h2>
            <p class="text-gray-600 text-sm">منظومة الأنشطة الطلابية</p>
            <div class="flex items-center justify-center gap-2 mt-3">
                <div class="h-px w-12 bg-gold/30"></div>
                <i class="fas fa-star text-gold text-xs"></i>
                <div class="h-px w-12 bg-gold/30"></div>
            </div>
        </div>

        <!-- عرض الأخطاء العامة -->
        @if ($errors->any())
            <div class="bg-red-50 border-2 border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                    <span class="font-bold text-sm">يوجد أخطاء:</span>
                </div>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- رسالة النجاح -->
        @if (session('status'))
            <div class="bg-green-50 border-2 border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                <i class="fas fa-check-circle text-green-500"></i>
                <span class="text-sm">{{ session('status') }}</span>
            </div>
        @endif

        <!-- نموذج تسجيل الدخول -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- البريد الإلكتروني -->
            <div>
                <label for="email" class="block text-navy font-bold mb-2 text-sm">
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
                    class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gold/50 focus:border-gold transition bg-gray-50 hover:bg-white"
                    placeholder="example@university.edu"
                >
                @error('email')
                    <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- كلمة المرور -->
            <div>
                <label for="password" class="block text-navy font-bold mb-2 text-sm">
                    <i class="fas fa-lock ml-2 text-gold"></i>
                    كلمة المرور
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gold/50 focus:border-gold transition bg-gray-50 hover:bg-white"
                    placeholder="••••••••"
                >
                @error('password')
                    <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- تذكرني ونسيت كلمة المرور -->
            <div class="flex items-center justify-between pt-2">
                <label class="flex items-center cursor-pointer group">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        class="w-4 h-4 text-gold border-2 border-gray-300 rounded focus:ring-gold cursor-pointer"
                    >
                    <span class="mr-2 text-gray-700 text-sm group-hover:text-navy transition">تذكرني</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-gold hover:text-yellow-700 text-sm font-bold transition hover:underline">
                        نسيت كلمة المرور؟
                    </a>
                @endif
            </div>

            <!-- زر تسجيل الدخول -->
            <button 
                type="submit" 
                class="w-full gradient-gold text-white font-black py-4 px-4 rounded-xl transition duration-300 shadow-lg hover:shadow-2xl transform hover:-translate-y-1 text-lg mt-6"
            >
                <i class="fas fa-sign-in-alt ml-2"></i>
                تسجيل الدخول
            </button>

            <!-- رابط التسجيل -->
            <div class="text-center pt-4">
                <p class="text-gray-600 text-sm">
                    ليس لديك حساب؟ 
                    <a href="{{ route('register') }}" class="text-gold hover:text-yellow-700 font-bold transition hover:underline">
                        سجل الآن
                    </a>
                </p>
            </div>
        </form>

        <!-- رابط العودة -->
        <div class="text-center mt-8 pt-6 border-t-2 border-gold/10">
            <a href="/" class="inline-flex items-center gap-2 text-gray-600 hover:text-navy text-sm font-semibold transition group">
                <i class="fas fa-arrow-right group-hover:-translate-x-1 transition"></i>
                العودة للصفحة الرئيسية
            </a>
        </div>
    </div>

</body>
</html>