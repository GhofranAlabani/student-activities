<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label class="block text-gray-700 font-bold mb-2">
                <i class="fas fa-envelope ml-2 text-indigo-600"></i>
                البريد الإلكتروني
            </label>
            <input 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                placeholder="example@university.edu"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label class="block text-gray-700 font-bold mb-2">
                <i class="fas fa-lock ml-2 text-indigo-600"></i>
                كلمة المرور
            </label>
            <input 
                type="password" 
                name="password" 
                required 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                placeholder="••••••••"
            >
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <span class="mr-2 text-gray-700">تذكرني</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm">
                    نسيت كلمة المرور؟
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition font-bold text-lg shadow-lg">
            <i class="fas fa-sign-in-alt ml-2"></i>
            تسجيل الدخول
        </button>

        <!-- Register Link -->
        <div class="text-center mt-4">
            <span class="text-gray-600">ما عندك حساب؟ </span>
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-bold">
                سجل الآن
            </a>
        </div>
    </form>
</x-guest-layout>