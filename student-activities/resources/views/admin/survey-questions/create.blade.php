@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6 max-w-3xl">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-plus-circle text-purple-600 ml-2"></i>
            إضافة سؤال جديد
        </h1>
        <p class="text-gray-600">أضف سؤالاً جديداً للاستبيان العام</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500 text-xl"></i>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Add Form -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <form action="{{ route('admin.survey-questions.store') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-3">
                    <i class="fas fa-question-circle text-purple-600 ml-2"></i>
                    نص السؤال
                </label>
                <textarea name="question" 
                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition resize-none"
                          rows="4" 
                          placeholder="اكتب نص السؤال هنا..."
                          required>{{ old('question') }}</textarea>
                @error('question') 
                    <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> 
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-3 rounded-xl hover:from-purple-700 hover:to-indigo-700 transition font-bold shadow-lg">
                    <i class="fas fa-save ml-2"></i>إضافة السؤال
                </button>
                <a href="{{ route('admin.survey-questions.index') }}" 
                   class="px-8 py-3 border-2 border-gray-300 rounded-xl hover:bg-gray-50 transition font-bold text-gray-700">
                    إلغاء
                </a>
            </div>
        </form>
    </div>

    <!-- Tips Box -->
    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-5 flex items-start gap-3">
        <i class="fas fa-lightbulb text-yellow-600 text-xl mt-1"></i>
        <div>
            <p class="font-bold text-yellow-900 mb-1">نصائح:</p>
            <ul class="text-yellow-700 text-sm list-disc list-inside space-y-1">
                <li>استخدم عناوين الأقسام مثل: "القسم الأول: تقييم المدرب"</li>
                <li>اجعل الأسئلة واضحة ومباشرة</li>
                <li>يمكنك إضافة أسئلة فرعية تحت كل قسم</li>
            </ul>
        </div>
    </div>
</div>
@endsection