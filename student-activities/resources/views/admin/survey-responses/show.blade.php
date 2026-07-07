@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-navy">
            <i class="fas fa-poll text-gold ml-2"></i>
            تفاصيل الإجابة
        </h1>
        <a href="{{ route('admin.survey-responses.index') }}" 
           class="bg-gray-200 text-navy px-4 py-2 rounded-lg hover:bg-gray-300 transition">
            <i class="fas fa-arrow-right ml-1"></i> رجوع
        </a>
    </div>
    
    <!-- معلومات الطالب -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-navy mb-4">معلومات الطالب</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-gray-500 text-sm">الاسم</p>
                <p class="font-bold text-navy">{{ $response->student_name }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">البريد الإلكتروني</p>
                <p class="font-bold text-navy">{{ $response->student_email }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">النشاط</p>
                <p class="font-bold text-navy">{{ $response->activity_title }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">التاريخ</p>
                <p class="font-bold text-navy">{{ \Carbon\Carbon::parse($response->created_at)->format('Y/m/d H:i') }}</p>
            </div>
        </div>
    </div>
    
    <!-- الإجابات -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-navy mb-4">الإجابات</h2>
        <div class="space-y-4">
            @foreach($answers as $answer)
                <div class="border-b border-gray-100 pb-4">
                    <p class="font-bold text-navy mb-2">{{ $answer->question }}</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        @if($answer->type === 'rating')
                            <div class="flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $answer->answer ? 'text-gold' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                        @else
                            <p class="text-gray-700">{{ $answer->answer }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection