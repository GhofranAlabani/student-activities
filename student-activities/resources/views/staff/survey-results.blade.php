@extends('layouts.staff')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- الهيدر -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-navy mb-2">نتائج استبيان النشاط</h1>
            <p class="text-gray-600">{{ $activity->title }}</p>
        </div>
        <a href="{{ route('staff.dashboard') }}" class="bg-gray-200 text-navy px-6 py-3 rounded-xl hover:bg-gray-300 transition">
            <i class="fas fa-arrow-right ml-2"></i> رجوع
        </a>
    </div>

    <!-- الإحصائيات -->
    @if(!empty($stats))
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        @foreach($stats as $stat)
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-navy mb-4">{{ $stat['question'] }}</h3>
                <div class="space-y-3">
                    @foreach($stat['responses'] as $option => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">{{ $option }}</span>
                            <div class="flex items-center gap-3">
                                <div class="w-32 bg-gray-200 rounded-full h-3">
                                    <div class="bg-gold h-3 rounded-full" 
                                         style="width: {{ $count > 0 ? ($count / count($responses) * 100) : 0 }}%"></div>
                                </div>
                                <span class="font-bold text-navy w-8">{{ $count }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    @endif

    <!-- الإجابات التفصيلية -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-2xl font-bold text-navy mb-6">الإجابات التفصيلية</h2>
        
        @if($responses->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right font-bold text-navy">الطالب</th>
                            <th class="px-4 py-3 text-right font-bold text-navy">السؤال</th>
                            <th class="px-4 py-3 text-right font-bold text-navy">الإجابة</th>
                            <th class="px-4 py-3 text-right font-bold text-navy">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($responses as $response)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div>
                                        <div class="font-bold text-navy">{{ $response->student_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $response->student_email }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-700">{{ $response->question }}</td>
                                <td class="px-4 py-3">
                                    @if($response->type === 'rating')
                                        <div class="flex gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $response->answer ? 'text-gold' : 'text-gray-300' }}"></i>
                                            @endfor
                                        </div>
                                    @else
                                        <span class="text-gray-700">{{ $response->answer }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $response->created_at->format('Y/m/d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 text-gray-400">
                <i class="fas fa-clipboard-list text-6xl mb-4"></i>
                <p class="text-lg">لا توجد إجابات حتى الآن</p>
            </div>
        @endif
    </div>
</div>
@endsection