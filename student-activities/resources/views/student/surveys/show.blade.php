@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-3xl">
    <h1 class="text-2xl font-bold mb-2">{{ $survey->title }}</h1>
    <p class="text-gray-600 mb-6">{{ $survey->description }}</p>

    <form action="{{ route('student.surveys.submit', $survey->activity_id) }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        @foreach($survey->questions as $question)
        <div class="mb-6">
            <label class="block font-semibold mb-2">
                {{ $question->question }}
                @if($question->required)
                <span class="text-red-500">*</span>
                @endif
            </label>

            @if($question->type === 'text')
            <input type="text" name="question_{{ $question->id }}" 
                   class="w-full border rounded px-3 py-2" 
                   {{ $question->required ? 'required' : '' }}>

            @elseif($question->type === 'radio')
            @foreach($question->options as $option)
            <label class="block mb-2">
                <input type="radio" name="question_{{ $question->id }}" 
                       value="{{ $option }}" 
                       {{ $question->required ? 'required' : '' }}>
                {{ $option }}
            </label>
            @endforeach

            @elseif($question->type === 'checkbox')
            @foreach($question->options as $option)
            <label class="block mb-2">
                <input type="checkbox" name="question_{{ $question->id }}[]" 
                       value="{{ $option }}">
                {{ $option }}
            </label>
            @endforeach

            @elseif($question->type === 'rating')
            <div class="flex gap-2">
                @for($i = 1; $i <= 5; $i++)
                <label>
                    <input type="radio" name="question_{{ $question->id }}" 
                           value="{{ $i }}" 
                           {{ $question->required ? 'required' : '' }}>
                    {{ $i }} 
                </label>
                @endfor
            </div>
            @endif
        </div>
        @endforeach

        <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded w-full">
            إرسال الإجابات
        </button>
    </form>
</div>
@endsection