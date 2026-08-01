@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <x-frontend.breadcrumbs :items="[
            ['label' => 'Teacher Dashboard', 'route' => route('teacher.dashboard')],
            ['label' => 'My Lessons', 'current' => true],
        ]" />

        <div class="mb-6">
            <h1 class="soh-page-title">My Lessons</h1>
            <p class="soh-page-subtitle">Create, update, and manage your lessons in one place.</p>
        </div>

        <livewire:backend.lessons.lesson-list route-prefix="teacher" />
    </div>
@endsection
