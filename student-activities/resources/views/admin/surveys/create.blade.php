@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">إنشاء استبيان لـ: {{ $activity->title }}</h1>

    <form action="{{ route('admin.surveys.store', $activity->id) }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">عنوان الاستبيان</label>
            <input type="text" name="title" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">الوصف</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" rows="3"></textarea>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded">
            إنشاء الاستبيان
        </button>
    </form>
</div>
@endsection