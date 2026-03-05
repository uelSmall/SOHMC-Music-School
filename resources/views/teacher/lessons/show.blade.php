@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-4 text-sm">
            <a href="{{ route('teacher.dashboard') }}" class="soh-link font-medium">Teacher Dashboard</a>
            <span class="mx-1 text-gray-400">/</span>
            <a href="{{ route('teacher.lessons.index') }}" class="soh-link font-medium">My Lessons</a>
            <span class="mx-1 text-gray-400">/</span>
            <span class="text-gray-600">{{ $lesson->title }}</span>
        </div>

        <div class="soh-card overflow-hidden p-0">
            <div class="bg-[var(--soh-purple-dark)] px-5 py-5 text-white sm:px-7">
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-bold">{{ $lesson->title }}</h1>
                    <span class="inline-flex items-center rounded-full border border-white/45 bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white">
                        {{ ucfirst($lesson->status->value) }}
                    </span>
                </div>
                @if ($lesson->description)
                    <p class="mt-2 text-sm text-white/90">{{ $lesson->description }}</p>
                @endif
                <p class="mt-2 text-xs font-medium text-white/80">Last updated: {{ $lesson->updated_at?->format('M d, Y \a\t h:i A') }}</p>
            </div>

            <div class="space-y-5 p-6">
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route($routePrefix.'.lessons.edit', $lesson) }}" class="soh-btn-primary">Edit Lesson</a>
                    @if ($lesson->file_path)
                        <a href="{{ Storage::url($lesson->file_path) }}" target="_blank" class="soh-btn-outline">View Material</a>
                    @endif
                </div>

                <div>
                    <h2 class="mb-2 text-lg font-semibold text-black">Lesson Content</h2>
                    <div class="whitespace-pre-line rounded-xl border border-[color:var(--soh-gray)] bg-[var(--soh-surface)] p-4">{{ $lesson->content }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
