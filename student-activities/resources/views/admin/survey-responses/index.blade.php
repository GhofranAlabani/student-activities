@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-navy mb-6">
        <i class="fas fa-poll text-gold ml-2"></i>
        إجابات الاستبيانات
    </h1>
    
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-navy text-white">
                <tr>
                    <th class="py-4 px-6 text-right">#</th>
                    <th class="py-4 px-6 text-right">الطالب</th>
                    <th class="py-4 px-6 text-right">النشاط</th>
                    <th class="py-4 px-6 text-right">التاريخ</th>
                    <th class="py-4 px-6 text-right">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($responses as $response)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-6">{{ $response->id }}</td>
                        <td class="py-4 px-6 font-bold">{{ $response->student_name }}</td>
                        <td class="py-4 px-6">{{ $response->activity_title }}</td>
                        <td class="py-4 px-6">{{ \Carbon\Carbon::parse($response->created_at)->format('Y/m/d H:i') }}</td>
                        <td class="py-4 px-6">
                            <a href="{{ route('admin.survey-responses.show', $response->id) }}" 
                               class="bg-gold text-navy px-4 py-2 rounded-lg hover:bg-gold-dark transition font-bold text-sm">
                                <i class="fas fa-eye ml-1"></i> عرض
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-400">
                            <i class="fas fa-inbox text-5xl mb-4"></i>
                            <p>لا توجد إجابات بعد</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $responses->links() }}
    </div>
</div>
@endsection