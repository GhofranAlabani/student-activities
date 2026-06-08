@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">{{ $survey->title }}</h1>

    <!-- إضافة سؤال جديد -->
    <div class="bg-white p-6 rounded shadow mb-6">
        <h2 class="text-lg font-semibold mb-4">إضافة سؤال جديد</h2>
        <form action="{{ route('admin.surveys.questions', $survey->id) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">نص السؤال</label>
                    <input type="text" name="question" class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">نوع السؤال</label>
                    <select name="type" class="w-full border rounded px-3 py-2" required>
                        <option value="text">نص</option>
                        <option value="radio">خيار واحد</option>
                        <option value="checkbox">خيارات متعددة</option>
                        <option value="rating">تقييم (1-5)</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">الخيارات (كل خيار في سطر)</label>
                    <textarea name="options" class="w-full border rounded px-3 py-2" rows="3"></textarea>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="required" id="required" class="mr-2">
                    <label for="required">سؤال إجباري</label>
                </div>
            </div>

            <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded mt-4">
                إضافة السؤال
            </button>
        </form>
    </div>

    <!-- قائمة الأسئلة -->
    <div class="space-y-4">
        @foreach($survey->questions as $question)
        <div class="bg-white p-6 rounded shadow">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-semibold">{{ $question->question }}</h3>
                    <p class="text-sm text-gray-500">النوع: {{ $question->type }}</p>
                    @if($question->options)
                    <ul class="mt-2 text-sm">
                        @foreach($question->options as $option)
                        <li>- {{ $option }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                <form action="{{ route('admin.surveys.questions.delete', $question->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500">حذف</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection