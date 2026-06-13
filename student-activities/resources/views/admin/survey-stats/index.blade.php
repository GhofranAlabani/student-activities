@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6 max-w-7xl">
    
    <!-- Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-3xl p-8 text-white shadow-2xl">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold mb-3 flex items-center gap-4">
                        <span class="bg-white/20 backdrop-blur-sm p-4 rounded-2xl">
                            <i class="fas fa-chart-bar text-3xl"></i>
                        </span>
                        إحصائيات الاستبيانات
                    </h1>
                    <p class="text-blue-100 text-lg mr-20">تحليل شامل لاستجابات الطلاب وتقييماتهم</p>
                </div>
                
                @if($totalResponses > 0)
                <div class="no-print">
                    <button onclick="window.print()" 
                            class="bg-white text-indigo-600 hover:bg-blue-50 px-8 py-4 rounded-2xl transition font-bold shadow-xl hover:shadow-2xl flex items-center gap-3 transform hover:scale-105">
                        <i class="fas fa-print text-xl"></i>
                        <span>طباعة / حفظ PDF</span>
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($totalResponses > 0)
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition border-r-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-2 font-semibold">إجمالي الأسئلة</p>
                        <p class="text-5xl font-bold text-gray-800">{{ $totalQuestions }}</p>
                        <p class="text-purple-600 text-sm mt-2">سؤال</p>
                    </div>
                    <div class="w-20 h-20 bg-purple-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-question text-purple-600 text-3xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition border-r-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-2 font-semibold">إجمالي الإجابات</p>
                        <p class="text-5xl font-bold text-gray-800">{{ $totalResponses }}</p>
                        <p class="text-blue-600 text-sm mt-2">إجابة</p>
                    </div>
                    <div class="w-20 h-20 bg-blue-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-blue-600 text-3xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition border-r-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-2 font-semibold">عدد الطلاب</p>
                        <p class="text-5xl font-bold text-gray-800">{{ $totalStudents }}</p>
                        <p class="text-green-600 text-sm mt-2">طالب</p>
                    </div>
                    <div class="w-20 h-20 bg-green-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-users text-green-600 text-3xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition border-r-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-2 font-semibold">نسبة المشاركة</p>
                        <p class="text-5xl font-bold text-gray-800">{{ $participationRate }}%</p>
                        <p class="text-orange-600 text-sm mt-2">مشاركة</p>
                    </div>
                    <div class="w-20 h-20 bg-orange-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-percentage text-orange-600 text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- توزيع الإجابات -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-pie text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">توزيع الإجابات العام</h3>
                </div>
                <div style="height: 350px;">
                    <canvas id="answersChart"></canvas>
                </div>
            </div>

            <!-- الإجابات حسب الأسئلة -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-bar text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">الإجابات على كل سؤال</h3>
                </div>
                <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                    @foreach($questionsWithAnswers as $question)
                        <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition bg-gray-50">
                            <p class="font-bold text-gray-800 mb-3 text-base">{{ Str::limit($question->question, 70) }}</p>
                            <div class="flex gap-3 flex-wrap">
                                @foreach(['موافق', 'محايد', 'أوافق بشدة'] as $answer)
                                    @php
                                        $response = $question->responses->firstWhere('answer', $answer);
                                        $count = $response->count ?? 0;
                                        $total = $question->responses->sum('count');
                                        $percentage = $total > 0 ? round(($count / $total) * 100) : 0;
                                        
                                        $colors = [
                                            'موافق' => 'bg-blue-100 text-blue-700 border-blue-300',
                                            'محايد' => 'bg-gray-100 text-gray-700 border-gray-300',
                                            'أوافق بشدة' => 'bg-green-100 text-green-700 border-green-300'
                                        ];
                                    @endphp
                                    <div class="{{ $colors[$answer] }} rounded-xl px-4 py-2 text-sm border-2">
                                        <span class="font-bold">{{ $answer }}:</span>
                                        <span class="font-bold text-lg mr-1">{{ $count }}</span>
                                        <span class="text-xs opacity-75">({{ $percentage }}%)</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Detailed Stats Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 px-6 py-5">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-table text-2xl"></i>
                    التفاصيل الكاملة للإجابات
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-right text-sm font-bold text-gray-700 uppercase">#</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-gray-700 uppercase">السؤال</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-gray-700 uppercase">الإجمالي</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-blue-600 uppercase">موافق</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-gray-600 uppercase">محايد</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-green-600 uppercase">أوافق بشدة</th>
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
                            <tr class="hover:bg-blue-50 transition">
                                <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $question->question }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-indigo-600 text-lg">{{ $total }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-blue-600">{{ $agree }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-600">{{ $neutral }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-green-600">{{ $strongAgree }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- No Data Message -->
        <div class="bg-white rounded-3xl shadow-lg p-20 text-center border-2 border-dashed border-gray-300">
            <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-chart-bar text-6xl text-gray-400"></i>
            </div>
            <h3 class="text-3xl font-bold text-gray-700 mb-3">لا توجد إجابات بعد</h3>
            <p class="text-gray-500 mb-8 text-lg">لم يقم أي طالب بالإجابة على الاستبيان حتى الآن</p>
            <a href="{{ route('activities.index') }}" class="inline-block bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-10 py-4 rounded-2xl transition font-bold text-lg shadow-xl">
                <i class="fas fa-arrow-left ml-2"></i>العودة للأنشطة
            </a>
        </div>
    @endif
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@if($totalResponses > 0 && $answersDistribution->count() > 0)
    const answersCtx = document.getElementById('answersChart').getContext('2d');
    new Chart(answersCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($answersDistribution->pluck('answer')) !!},
            datasets: [{
                data: {!! json_encode($answersDistribution->pluck('count')) !!},
                backgroundColor: [
                    '#3B82F6',
                    '#8B5CF6',
                    '#10B981'
                ],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    rtl: true,
                    labels: {
                        padding: 20,
                        font: {
                            size: 14,
                            family: 'Cairo',
                            weight: 'bold'
                        },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 15,
                    titleFont: { size: 16, family: 'Cairo' },
                    bodyFont: { size: 14, family: 'Cairo' },
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed + ' إجابة';
                            return label;
                        }
                    }
                }
            }
        }
    });
@endif
</script>

<style>
    @media print {
        .sidebar, .no-print, button, nav {
            display: none !important;
        }
        .container {
            max-width: 100% !important;
            padding: 0 !important;
        }
        .bg-gradient-to-r, .bg-gradient-to-br {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        table {
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        @page {
            margin: 1.5cm;
            size: landscape;
        }
    }
</style>
@endsection