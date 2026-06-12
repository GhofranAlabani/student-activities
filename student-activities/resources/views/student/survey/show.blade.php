@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-6 mb-6 text-white shadow-lg">
        <div class="flex items-center gap-3 mb-2">
            <i class="fas fa-poll text-3xl"></i>
            <h1 class="text-2xl font-bold">استبيان تقييم النشاط</h1>
        </div>
        <p class="opacity-90">{{ $activity->title }}</p>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex items-start gap-3">
        <i class="fas fa-info-circle text-blue-600 text-xl mt-1"></i>
        <div>
            <p class="font-bold text-blue-900 mb-1">رأيك يهمنا!</p>
            <p class="text-blue-700 text-sm">
                نرجو منك الإجابة على جميع الأسئلة لمساعدتنا في تحسين الأنشطة القادمة
            </p>
        </div>
    </div>

    <!-- Survey Form -->
    <form action="{{ route('student.survey.submit', $activity->id) }}" method="POST" 
          class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
        @csrf
        
        <div class="space-y-8">
            @php
                $currentSection = '';
                $questionNumber = 0;
            @endphp

            @forelse($questions as $question)
                @if(str_starts_with($question->question, 'القسم'))
                    {{-- Section Header --}}
                    @php $currentSection = $question->question; @endphp
                    <div class="mt-8 mb-4">
                        <div class="bg-gradient-to-r from-purple-100 to-indigo-100 border-2 border-purple-300 rounded-xl p-4">
                            <h2 class="text-xl font-bold text-purple-800 flex items-center gap-2">
                                <i class="fas fa-folder-open"></i>
                                {{ $question->question }}
                            </h2>
                        </div>
                    </div>
                @else
                    @php $questionNumber++; @endphp
                    {{-- Regular Question --}}
                    <div class="border-b border-gray-200 pb-6 last:border-b-0">
                        <div class="flex items-start gap-3 mb-4">
                            <span class="bg-purple-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold flex-shrink-0 text-sm">
                                {{ $questionNumber }}
                            </span>
                            <h3 class="font-bold text-lg text-gray-800 pt-1 flex-1">{{ $question->question }}</h3>
                        </div>
                        
                        <div class="flex gap-3 flex-wrap mr-11">
                            @foreach(['موافق', 'محايد', 'أوافق بشدة'] as $option)
                            <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-3 rounded-xl transition border-2 border-gray-200 hover:border-purple-300">
                                <input type="radio" name="answers[{{ $question->id }}]" 
                                       value="{{ $option }}" 
                                       required
                                       class="w-5 h-5 text-purple-600 focus:ring-purple-500">
                                <span class="px-4 py-2 rounded-lg font-semibold text-gray-700 hover:text-purple-700">
                                    {{ $option }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-5xl mb-3"></i>
                    <p class="text-lg">لا توجد أسئلة متاحة حالياً</p>
                </div>
            @endforelse
        </div>

        @if($questions->count() > 0)
        <!-- Submit Buttons -->
        <div class="mt-8 flex gap-3">
            <button type="submit" 
                    class="flex-1 bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-4 rounded-xl hover:from-purple-700 hover:to-indigo-700 transition font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <i class="fas fa-paper-plane ml-2"></i>إرسال الإجابات
            </button>
            <a href="{{ route('activities.show', $activity->id) }}" 
               class="px-8 py-4 border-2 border-gray-300 rounded-xl hover:bg-gray-50 transition font-bold text-lg text-gray-700">
                إلغاء
            </a>
        </div>
        @endif
    </form>

    <!-- Footer Note -->
    <div class="text-center mt-6 text-gray-500 text-sm">
        <i class="fas fa-lock ml-1"></i>
        إجاباتك سرية وتُستخدم فقط لأغراض التحسين
    </div>
</div>
@endsection