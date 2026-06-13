@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6">
    
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2 flex items-center gap-3">
                    <span class="bg-gradient-to-br from-purple-600 to-indigo-600 text-white p-3 rounded-xl shadow-lg">
                        <i class="fas fa-poll text-2xl"></i>
                    </span>
                    إدارة أسئلة الاستبيان العام
                </h1>
                <p class="text-gray-600 mr-16">يمكنك إضافة أو تعديل أو حذف أسئلة الاستبيان</p>
            </div>
            <div class="flex gap-3">
                <form action="{{ route('admin.survey-questions.reset') }}" method="POST" 
                      onsubmit="return confirm('هل أنت متأكد من إعادة الأسئلة الافتراضية؟ سيتم حذف جميع الأسئلة الحالية!')">
                    @csrf
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl transition font-semibold shadow-lg hover:shadow-xl flex items-center gap-2">
                        <i class="fas fa-redo"></i>
                        <span>الأسئلة الافتراضية</span>
                    </button>
                </form>
                <a href="{{ route('admin.survey-questions.create') }}" 
                   class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white px-5 py-3 rounded-xl transition font-semibold shadow-lg hover:shadow-xl flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>إضافة سؤال</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-r-4 border-green-500 text-green-700 px-6 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-md">
            <i class="fas fa-check-circle text-green-500 text-2xl"></i>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm mb-1">إجمالي الأسئلة</p>
                    <p class="text-4xl font-bold">{{ $questions->count() }}</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-question text-3xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm mb-1">آخر تحديث</p>
                    <p class="text-xl font-semibold">
                        {{ $questions->max('created_at') ? $questions->max('created_at')->format('Y/m/d') : '-' }}
                    </p>
                    <p class="text-sm text-blue-100">
                        {{ $questions->max('created_at') ? $questions->max('created_at')->format('H:i') : '' }}
                    </p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-clock text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm mb-1">الحالة</p>
                    <p class="text-2xl font-bold">نشط</p>
                    <p class="text-sm text-green-100">الاستبيان متاح</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Questions List -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-5">
            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                <i class="fas fa-list-ul text-2xl"></i>
                <span>قائمة الأسئلة</span>
            </h2>
        </div>

        @if($questions->count() > 0)
            <div class="divide-y divide-gray-100">
                @php $uniqueQuestions = $questions->unique('question')->values(); @endphp
                @foreach($uniqueQuestions as $index => $question)
                <div class="p-6 hover:bg-gray-50 transition group border-r-4 border-transparent hover:border-purple-500">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4 flex-1">
                            <span class="bg-gradient-to-br from-purple-500 to-indigo-600 text-white w-12 h-12 rounded-xl flex items-center justify-center font-bold shadow-md text-lg">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-1">
                                <p class="font-bold text-gray-800 text-lg mb-2">{{ $question->question }}</p>
                                <div class="flex items-center gap-6 text-sm text-gray-500">
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                        {{ $question->created_at->format('Y/m/d') }}
                                    </span>
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-clock text-gray-400"></i>
                                        {{ $question->created_at->format('H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('admin.survey-questions.edit', $question->id) }}" 
                               class="bg-blue-50 hover:bg-blue-100 text-blue-600 p-3 rounded-xl transition"
                               title="تعديل">
                                <i class="fas fa-edit text-lg"></i>
                            </a>
                            <form action="{{ route('admin.survey-questions.destroy', $question->id) }}" 
                                  method="POST"
                                  onsubmit="return confirm('هل تريد حذف هذا السؤال؟')">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-50 hover:bg-red-100 text-red-600 p-3 rounded-xl transition"
                                        title="حذف">
                                    <i class="fas fa-trash-alt text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 text-gray-400">
                <i class="fas fa-inbox text-7xl mb-4 text-gray-300"></i>
                <p class="text-xl font-semibold mb-2 text-gray-600">لا توجد أسئلة</p>
                <p class="text-sm text-gray-500 mb-6">أضف أسئلة جديدة أو استخدم الأسئلة الافتراضية</p>
                <a href="{{ route('admin.survey-questions.create') }}" 
                   class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl transition font-semibold">
                    <i class="fas fa-plus ml-2"></i>إضافة أول سؤال
                </a>
            </div>
        @endif
    </div>

    <!-- Info Box -->
    <div class="mt-6 bg-gradient-to-l from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 flex items-start gap-4 shadow-sm">
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas fa-info-circle text-blue-600 text-2xl"></i>
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-blue-900 mb-2 text-lg">معلومات مهمة</h3>
            <p class="text-blue-700 leading-relaxed">
                هذه الأسئلة ستظهر للطلاب بعد إكمال كل نشاط. يمكن للطلاب الإجابة بـ: 
                <span class="font-semibold">موافق</span>، 
                <span class="font-semibold">محايد</span>، أو 
                <span class="font-semibold">لا اوافق </span>
            </p>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .group:hover .group-hover\:opacity-100 {
        opacity: 1;
    }
</style>
@endsection