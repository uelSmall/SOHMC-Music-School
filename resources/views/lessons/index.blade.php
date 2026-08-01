@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
    @php
        $breadcrumbItems = [];

        if (auth()->user()->hasRole('student')) {
            $breadcrumbItems = [
                ['label' => 'Student Dashboard', 'route' => route('student.dashboard')],
                ['label' => 'My Lessons', 'current' => true],
            ];
        } elseif (auth()->user()->hasRole('parent')) {
            $breadcrumbItems = [
                ['label' => 'Parent Dashboard', 'route' => route('parent.dashboard')],
                ['label' => 'Children’s Lessons', 'current' => true],
            ];
        } elseif (auth()->user()->hasRole('teacher')) {
            $breadcrumbItems = [
                ['label' => 'Teacher Dashboard', 'route' => route('teacher.dashboard')],
                ['label' => 'My Lessons', 'current' => true],
            ];
        } else {
            $breadcrumbItems = [
                ['label' => 'Admin Dashboard', 'route' => route('backend.dashboard')],
                ['label' => 'Lessons', 'current' => true],
            ];
        }
    @endphp

    <x-frontend.breadcrumbs :items="$breadcrumbItems" />

    <div class="mb-5">
        <h1 class="soh-page-title">My Lessons</h1>
        <p class="soh-page-subtitle">Browse lessons, filter quickly, and manage assignment progress.</p>
    </div>

    <livewire:frontend.lessons.lesson-search />
</div>
@endsection
