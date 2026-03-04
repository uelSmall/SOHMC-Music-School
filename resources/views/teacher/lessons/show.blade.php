@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="soh-card overflow-hidden p-0">
            <div class="px-5 py-5 text-white sm:px-7" style="background:#8C0375;">
                <h1 class="text-2xl font-bold">{{ $lesson->title }}</h1>
                @if ($lesson->description)
                    <p class="mt-2 text-sm text-white/90">{{ $lesson->description }}</p>
                @endif
            </div>

            <div class="space-y-5 p-6">
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route($routePrefix.'.lessons.edit', $lesson) }}" class="soh-btn-primary">Edit Lesson</a>
                    <a href="{{ route($routePrefix.'.lessons.index') }}" class="soh-btn-outline">Back to Lessons</a>
                    @if ($lesson->file_path)
                        <a href="{{ Storage::url($lesson->file_path) }}" target="_blank" class="soh-btn-outline">View Material</a>
                    @endif
                </div>

                <div>
                    <h2 class="mb-2 text-lg font-semibold text-black">Instructional Notes</h2>
                    <div class="rounded-xl border p-4" style="border-color:#D991CD; background:#F2F2F2; white-space: pre-line;">{{ $lesson->content }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
