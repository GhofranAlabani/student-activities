@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-[#f8f6f0] p-6 md:p-8">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header Section -->
        <div class="mb-8 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.all-registrations') }}" 
                   class="w-12 h-12 bg-white rounded-xl shadow-sm border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-amber-50 hover:text-amber-500 hover:border-amber-200 transition-all">
                    <i class="fas fa-arrow-right text-lg"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-slate-800 mb-1">تعديل التسجيل</h1>
                    <p class="text-slate-500">قم بتعديل بيانات تسجيل الطالب في النشاط</p>
                </div>
            </div>
            
            <!-- Registration ID Badge -->
            <div class="bg-white px-5 py-3 rounded-xl shadow-sm border border-slate-200 flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hashtag text-amber-600"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-semibold">رقم التسجيل</p>
                    <p class="text-lg font-bold text-slate-800">#{{ $registration->id }}</p>
                </div>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
            
            <!-- Card Header with Gradient -->
            <div class="bg-gradient-to-l from-blue-600 to-indigo-700 p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
                
                <div class="relative z-10 flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30">
                        <i class="fas fa-user-graduate text-white text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white mb-1">بيانات التسجيل</h2>
                        <p class="text-blue-100 text-sm">معلومات الطالب والنشاط المسجل فيه</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8">
                
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 mb-6 flex items-start gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-red-800 mb-2 text-lg">يوجد بعض الأخطاء:</h3>
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="text-red-700 text-sm flex items-center gap-2">
                                        <i class="fas fa-circle text-xs"></i>
                                        {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.registrations.update', $registration->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        
                        <!-- Student Field -->
                        <div>
                            <label class="block text-slate-700 font-bold mb-3 flex items-center gap-2">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600 text-sm"></i>
                                </div>
                                الطالب
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <select name="student_id" 
                                        class="w-full px-4 py-3.5 pr-12 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none bg-slate-50 group-hover:bg-white group-hover:border-blue-300">
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ $registration->student_id == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }} ({{ $student->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <i class="fas fa-chevron-down text-slate-400 group-hover:text-blue-500 transition-colors"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Field -->
                        <div>
                            <label class="block text-slate-700 font-bold mb-3 flex items-center gap-2">
                                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-amber-600 text-sm"></i>
                                </div>
                                النشاط
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <select name="activity_id" 
                                        class="w-full px-4 py-3.5 pr-12 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all appearance-none bg-slate-50 group-hover:bg-white group-hover:border-amber-300">
                                    @foreach($activities as $activity)
                                        <option value="{{ $activity->id }}" {{ $registration->activity_id == $activity->id ? 'selected' : '' }}>
                                            {{ $activity->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <i class="fas fa-chevron-down text-slate-400 group-hover:text-amber-500 transition-colors"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Status Field - Radio Buttons Style -->
                        <div>
                            <label class="block text-slate-700 font-bold mb-3 flex items-center gap-2">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-check-circle text-green-600 text-sm"></i>
                                </div>
                                الحالة
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-3 gap-4">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="status" value="مسجل" 
                                           {{ ($registration->status ?? 'مسجل') == 'مسجل' ? 'checked' : '' }}
                                           class="peer sr-only">
                                    <div class="text-center py-4 px-3 border-2 border-slate-200 rounded-xl peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:bg-slate-50 hover:border-blue-300 transition-all font-semibold group-hover:shadow-md">
                                        <i class="fas fa-user-clock text-2xl mb-2 block"></i>
                                        <span class="text-sm">مسجل</span>
                                    </div>
                                </label>
                                
                                <label class="cursor-pointer group">
                                    <input type="radio" name="status" value="مؤكد" 
                                           {{ ($registration->status ?? 'مسجل') == 'مؤكد' ? 'checked' : '' }}
                                           class="peer sr-only">
                                    <div class="text-center py-4 px-3 border-2 border-slate-200 rounded-xl peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-700 hover:bg-slate-50 hover:border-green-300 transition-all font-semibold group-hover:shadow-md">
                                        <i class="fas fa-check-double text-2xl mb-2 block"></i>
                                        <span class="text-sm">مؤكد</span>
                                    </div>
                                </label>
                                
                                <label class="cursor-pointer group">
                                    <input type="radio" name="status" value="ملغي" 
                                           {{ ($registration->status ?? 'مسجل') == 'ملغي' ? 'checked' : '' }}
                                           class="peer sr-only">
                                    <div class="text-center py-4 px-3 border-2 border-slate-200 rounded-xl peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700 hover:bg-slate-50 hover:border-red-300 transition-all font-semibold group-hover:shadow-md">
                                        <i class="fas fa-times-circle text-2xl mb-2 block"></i>
                                        <span class="text-sm">ملغي</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="mt-8 bg-gradient-to-l from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-5 flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                        </div>
                        <div class="text-sm text-blue-800">
                            <p class="font-bold mb-1 text-base">معلومات مهمة:</p>
                            <p class="leading-relaxed">تأكد من صحة البيانات قبل الحفظ. سيتم إرسال إشعار للطالب في حال تغيير حالة التسجيل.</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 mt-8 pt-6 border-t border-slate-200">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-l from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-4 px-6 rounded-xl transition-all transform hover:scale-[1.02] shadow-lg shadow-blue-500/30 flex items-center justify-center gap-3">
                            <i class="fas fa-save text-lg"></i>
                            حفظ التعديلات
                        </button>
                        <a href="{{ route('admin.all-registrations') }}" 
                           class="px-8 py-4 border-2 border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-50 hover:border-slate-400 transition-all text-center flex items-center justify-center gap-2">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<style>
    select {
        background-image: none;
    }
    
    /* Custom scrollbar for better UX */
    ::-webkit-scrollbar {
        width: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection