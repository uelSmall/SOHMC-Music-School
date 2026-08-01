@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        <x-frontend.breadcrumbs :items="[
            ['label' => 'Student Dashboard', 'route' => route('student.dashboard')],
            ['label' => 'My Lessons', 'current' => true],
        ]" />

        <div class="flex flex-col gap-3">
            <h1 class="soh-page-title">My Lessons</h1>
            <p class="soh-page-subtitle">Review your upcoming, completed, and cancelled lessons.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Upcoming Lessons</p>
                <p class="soh-kpi-value mt-2">{{ $upcomingLessons->count() }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Completed Lessons</p>
                <p class="soh-kpi-value mt-2">{{ $completedLessons->count() }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Cancelled Lessons</p>
                <p class="soh-kpi-value mt-2">{{ $cancelledLessons->count() }}</p>
            </div>
        </div>

        @php
            $lessonSections = [
                ['title' => 'Upcoming Lessons', 'items' => $upcomingLessons, 'empty' => 'No upcoming lessons scheduled.', 'badge' => 'bg-blue-100 text-blue-800'],
                ['title' => 'Completed Lessons', 'items' => $completedLessons, 'empty' => 'No completed lessons yet.', 'badge' => 'bg-green-100 text-green-800'],
                ['title' => 'Cancelled Lessons', 'items' => $cancelledLessons, 'empty' => 'No cancelled lessons yet.', 'badge' => 'bg-gray-100 text-gray-800'],
            ];
        @endphp

        <div class="grid gap-6 lg:grid-cols-2">
            @foreach ($lessonSections as $section)
                <section class="soh-card p-6">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <h2 class="text-xl font-semibold text-black">{{ $section['title'] }}</h2>
                    </div>

                    <div class="space-y-3">
                        @forelse ($section['items'] as $lesson)
                            <article class="rounded-2xl border border-gray-200 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="font-semibold text-black">{{ $lesson->teacher?->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $lesson->instrument?->name }}</div>
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $section['badge'] }}">{{ $lesson->status->label() }}</span>
                                </div>

                                <div class="mt-3 grid gap-2 text-sm text-gray-600 sm:grid-cols-2">
                                    <div>Date: {{ $lesson->lesson_date?->format('M d, Y') }}</div>
                                    <div>Time: {{ \Illuminate\Support\Carbon::parse($lesson->lesson_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($lesson->lesson_end_time)->format('g:i A') }}</div>
                                    <div>Duration: {{ $lesson->lesson_duration }} minutes</div>
                                    <div><a href="{{ route('student.lesson-management.show', $lesson) }}" class="soh-link text-sm font-medium">View details</a></div>
                                </div>
                            </article>
                        @empty
                            <p class="rounded-2xl border border-dashed border-gray-300 p-6 text-sm text-gray-600">{{ $section['empty'] }}</p>
                        @endforelse
                    </div>
                </section>
            @endforeach
        </div>
    </div>
@endsection