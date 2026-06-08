@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">إدارة الاستبيانات</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($surveys as $survey)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold">{{ $survey->title }}</h3>
            <p class="text-gray-600 text-sm mb-2">{{ $survey->activity->title }}</p>
            <p class="text-sm text-gray-500 mb-4">{{ $survey->questions_count }} أسئلة</p>

            <div class="flex gap-2">
                <a href="{{ route('admin.surveys.show', $survey->id) }}" 
                   class="bg-blue-500 text-white px-4 py-2 rounded text-sm">
                    عرض
                </a>
                <form action="{{ route('admin.surveys.destroy', $survey->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded text-sm">
                        حذف
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection