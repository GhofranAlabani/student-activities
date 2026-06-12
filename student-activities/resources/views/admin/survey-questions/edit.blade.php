@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6 max-w-3xl">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-edit text-blue-600 ml-2"></i>
            تعديل السؤال
        </h1>
        <p class="text-gray-600">يمكنك تعديل نص السؤال هنا</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500 text-xl"></i>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Edit Form -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <form action="{{ route('admin.survey-questions.update', $question->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-3">
                    <i class="fas fa-question-circle text-purple-600 ml-2"></i>
                    نص السؤال
                </label>
                <textarea name="question" 
                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition resize-none"
                          rows="4" 
                          required>{{ old('question', $question->question) }}</textarea>
                @error('question') 
                    <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> 
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition font-bold shadow-lg">
                    <i class="fas fa-save ml-2"></i>حفظ التعديلات
                </button>
                <a href="{{ route('admin.survey-questions.index') }}" 
                   class="px-8 py-3 border-2 border-gray-300 rounded-xl hover:bg-gray-50 transition font-bold text-gray-700">
                    إلغاء
                </a>
            </div>
        </form>
    </div>

    <!-- Current Question Preview -->
    <div class="mt-6 bg-gray-50 border border-gray-200 rounded-xl p-5">
        <h3 class="font-bold text-gray-700 mb-3">
            <i class="fas fa-eye text-gray-500 ml-2"></i>
            المعاينة الحالية:
        </h3>
        <p class="text-gray-800 text-lg">{{ $question->question }}</p>
    </div>
</div>
@endsection