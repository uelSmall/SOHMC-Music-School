@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <x-frontend.breadcrumbs :items="[
            ['label' => 'Teacher Dashboard', 'route' => route('teacher.dashboard')],
            ['label' => 'My Lessons', 'route' => route('teacher.lessons.index')],
            ['label' => 'Create Lesson', 'current' => true],
        ]" />

        <div class="mb-6">
            <h1 class="soh-page-title">Create Lesson</h1>
            <p class="soh-page-subtitle">Build a lesson and assign it to your students.</p>
        </div>

        <livewire:backend.lessons.lesson-form route-prefix="teacher" />
    </div>
@endsection
