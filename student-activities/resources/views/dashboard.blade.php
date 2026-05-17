<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Message -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-xl p-8 mb-8">
                <h2 class="text-3xl font-bold text-white mb-2">
                    👋 مرحباً {{ Auth::user()->name }}!
                </h2>
                <p class="text-white/80">لوحة تحكم الأنشطة الطلابية</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-lg p-6 border-r-4 border-indigo-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">الأنشطة المسجلة</p>
                            <p class="text-3xl font-bold text-gray-800">0</p>
                        </div>
                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-indigo-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 border-r-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">النقاط المكتسبة</p>
                            <p class="text-3xl font-bold text-gray-800">0</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-star text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 border-r-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">الشهادات</p>
                            <p class="text-3xl font-bold text-gray-800">0</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-certificate text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-bolt ml-2 text-yellow-500"></i>
                    الوصول السريع
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="{{ route('activities.index') }}" class="flex items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                        <div class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center text-white ml-4">
                            <i class="fas fa-search"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">تصفح الأنشطة</p>
                            <p class="text-sm text-gray-600">اكتشف الأنشطة المتاحة</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white ml-4">
                            <i class="fas fa-list"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">أنشطتي</p>
                            <p class="text-sm text-gray-600">الأنشطة المسجلة</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                        <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white ml-4">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">المفضلة</p>
                            <p class="text-sm text-gray-600">أنشطتي المفضلة</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>