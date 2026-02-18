@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-8">My Lessons</h1>

    @foreach($lessonsByInstrument as $instrument => $lessons)
        <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ ucfirst($instrument) }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            @foreach($lessons as $lesson)
                <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $lesson->title }}</h3>
                    <p class="text-sm text-gray-500 mb-2">Status: {{ ucfirst($lesson->status?->value ?? (string) $lesson->status ?? 'unknown') }}</p>
                    <p class="text-gray-700">{{ $lesson->description }}</p>
                </div>
            @endforeach
        </div>
    @endforeach

    @if($lessonsByInstrument->isEmpty())
        <p class="text-gray-600">No lessons found.</p>
    @endif
</div>
@endsection
