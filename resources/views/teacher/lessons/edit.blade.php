@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-4 text-sm">
            <a href="{{ route('teacher.dashboard') }}" class="font-medium" style="color:#A6128D;">Teacher Dashboard</a>
            <span class="mx-1 text-gray-400">/</span>
            <a href="{{ route('teacher.lessons.index') }}" class="font-medium" style="color:#A6128D;">My Lessons</a>
            <span class="mx-1 text-gray-400">/</span>
            <span class="text-gray-600">Edit Lesson</span>
        </div>

        <div class="mb-6">
            <h1 class="soh-page-title">Edit Lesson</h1>
            <p class="soh-page-subtitle">Update your lesson details, file, and student assignments.</p>
            <div class="mt-3 flex flex-wrap gap-3">
                <a href="{{ route('teacher.lessons.index') }}" class="soh-btn-outline">Back to Lessons</a>
                <a href="{{ route('teacher.dashboard') }}" class="soh-btn-outline">Back to Dashboard</a>
            </div>
        </div>

        <livewire:backend.lessons.lesson-form :lesson="$lesson" route-prefix="teacher" />
    </div>
@endsection
