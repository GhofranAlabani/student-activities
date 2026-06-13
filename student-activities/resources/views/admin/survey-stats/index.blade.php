@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2 flex items-center gap-3">
            <span class="bg-gradient-to-br from-blue-600 to-indigo-600 text-white p-3 rounded-xl shadow-lg">
                <i class="fas fa-chart-bar text-2xl"></i>
            </span>
            إحصائيات الاستبيانات
        </h1>
        <p class="text-gray-600 mr-16">نظرة شاملة على استجابات الطلاب للاستبيانات</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm mb-1">إجمالي الأسئلة</p>
                    <p class="text-4xl font-bold">{{ $totalQuestions }}</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-question text-3xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm mb-1">إجمالي الإجابات</p>
                    <p class="text-4xl font-bold">{{ $totalResponses }}</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm mb-1">عدد الطلاب</p>
                    <p class="text-4xl font-bold">{{ $totalStudents }}</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-users text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm mb-1">نسبة المشاركة</p>
                    <p class="text-3xl font-bold">{{ $participationRate }}%</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-percentage text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- توزيع الإجابات -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-blue-600"></i>
                توزيع الإجابات العام
            </h3>
            <canvas id="answersChart" height="300"></canvas>
        </div>

        <!-- إحصائيات الأسئلة -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-bar text-purple-600"></i>
                الإجابات على كل سؤال
            </h3>
            <div class="space-y-4 max-h-80 overflow-y-auto">
                @foreach($questionsWithAnswers as $question)
                    <div class="border-b border-gray-200 pb-4 last:border-b-0">
                        <p class="font-bold text-gray-700 mb-2 text-sm">{{ Str::limit($question->question, 60) }}</p>
                        <div class="flex gap-2 flex-wrap">
                            @foreach(['موافق', 'محايد', 'أوافق بشدة'] as $answer)
                                @php
                                    $response = $question->responses->firstWhere('answer', $answer);
                                    $count = $response->count ?? 0;
                                    $total = $question->responses->sum('count');
                                    $percentage = $total > 0 ? round(($count / $total) * 100) : 0;
                                @endphp
                                <div class="bg-gray-100 rounded-lg px-3 py-2 text-sm">
                                    <span class="font-semibold">{{ $answer }}:</span>
                                    <span class="text-blue-600 font-bold">{{ $count }}</span>
                                    <span class="text-gray-500 text-xs">({{ $percentage }}%)</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Detailed Stats Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-table"></i>
                التفاصيل الكاملة
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">السؤال</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عدد الإجابات</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">موافق</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">محايد</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">أوافق بشدة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($questionsWithAnswers as $index => $question)
                        @php
                            $total = $question->responses->sum('count');
                            $agree = $question->responses->firstWhere('answer', 'موافق')?->count ?? 0;
                            $neutral = $question->responses->firstWhere('answer', 'محايد')?->count ?? 0;
                            $strongAgree = $question->responses->firstWhere('answer', 'أوافق بشدة')?->count ?? 0;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $question->question }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-blue-600">{{ $total }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $agree }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $neutral }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $strongAgree }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // توزيع الإجابات
    const answersCtx = document.getElementById('answersChart').getContext('2d');
    new Chart(answersCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($answersDistribution->pluck('answer')) !!},
            datasets: [{
                data: {!! json_encode($answersDistribution->pluck('count')) !!},
                backgroundColor: [
                    '#8B5CF6',
                    '#3B82F6',
                    '#10B981'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    rtl: true
                }
            }
        }
    });
</script>
@endsection