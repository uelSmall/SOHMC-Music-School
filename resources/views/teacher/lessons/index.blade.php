@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-4 text-sm">
            <a href="{{ route('teacher.dashboard') }}" class="soh-link font-medium">Teacher Dashboard</a>
            <span class="mx-1 text-gray-400">/</span>
            <span class="text-gray-600">My Lessons</span>
        </div>

        <div class="mb-6">
            <h1 class="soh-page-title">My Lessons</h1>
            <p class="soh-page-subtitle">Create, update, and manage your lessons in one place.</p>
        </div>

        <livewire:backend.lessons.lesson-list route-prefix="teacher" />
    </div>
@endsection
