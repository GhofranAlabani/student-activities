<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-500 to-purple-600 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
        <!-- الشعار والعنوان -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-graduation-cap text-4xl text-indigo-600"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800">تسجيل الدخول</h2>
            <p class="text-gray-600 mt-2">نظام الأنشطة الطلابية</p>
        </div>

        <!-- عرض الأخطاء العامة -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- رسالة النجاح -->
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('status') }}
            </div>
        @endif

        <!-- نموذج تسجيل الدخول -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- البريد الإلكتروني -->
            <div class="mb-6">
                <label for="email" class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-envelope ml-2 text-indigo-600"></i>
                    البريد الإلكتروني
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="example@university.edu"
                >
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- كلمة المرور -->
            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-lock ml-2 text-indigo-600"></i>
                    كلمة المرور
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="••••••••"
                >
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- تذكرني -->
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                    >
                    <span class="mr-2 text-gray-700">تذكرني</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                        نسيت كلمة المرور؟
                    </a>
                @endif
            </div>

            <!-- زر تسجيل الدخول -->
            <button 
                type="submit" 
                class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
            >
                <i class="fas fa-sign-in-alt ml-2"></i>
                تسجيل الدخول
            </button>

            <!-- رابط التسجيل -->
            <div class="text-center mt-6">
                <p class="text-gray-600">
                    ليس لديك حساب؟ 
                    <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                        سجل الآن
                    </a>
                </p>
            </div>
        </form>

        <!-- رابط العودة -->
        <div class="text-center mt-6 pt-6 border-t border-gray-200">
            <a href="/" class="text-gray-600 hover:text-gray-800 text-sm">
                <i class="fas fa-arrow-right ml-1"></i>
                العودة للصفحة الرئيسية
            </a>
        </div>
    </div>

</body>
</html>