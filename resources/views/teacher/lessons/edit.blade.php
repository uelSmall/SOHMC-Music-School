@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <x-frontend.breadcrumbs :items="[
            ['label' => 'Teacher Dashboard', 'route' => route('teacher.dashboard')],
            ['label' => 'My Lessons', 'route' => route('teacher.lessons.index')],
            ['label' => 'Edit Lesson', 'current' => true],
        ]" />

        <div class="mb-6">
            <h1 class="soh-page-title">Edit Lesson</h1>
            <p class="soh-page-subtitle">Update your lesson details, file, and student assignments.</p>
        </div>

        <livewire:backend.lessons.lesson-form :lesson="$lesson" route-prefix="teacher" />
    </div>
@endsection
