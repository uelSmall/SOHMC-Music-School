@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">
        @php
            if (auth()->user()->hasRole('student')) {
                $breadcrumbItems = [
                    ['label' => 'Student Dashboard', 'route' => route('student.dashboard')],
                    ['label' => 'My Lessons', 'route' => route('lessons.index')],
                    ['label' => $lesson->title, 'current' => true],
                ];
            } elseif (auth()->user()->hasRole('parent')) {
                $breadcrumbItems = [
                    ['label' => 'Parent Dashboard', 'route' => route('parent.dashboard')],
                    ['label' => 'Children’s Lessons', 'route' => route('lessons.index')],
                    ['label' => $lesson->title, 'current' => true],
                ];
            } elseif (auth()->user()->hasRole('teacher')) {
                $breadcrumbItems = [
                    ['label' => 'Teacher Dashboard', 'route' => route('teacher.dashboard')],
                    ['label' => 'My Lessons', 'route' => route('lessons.index')],
                    ['label' => $lesson->title, 'current' => true],
                ];
            } else {
                $breadcrumbItems = [
                    ['label' => 'Admin Dashboard', 'route' => route('admin.dashboard')],
                    ['label' => 'Lessons', 'route' => route('lessons.index')],
                    ['label' => $lesson->title, 'current' => true],
                ];
            }
        @endphp

        <x-frontend.breadcrumbs :items="$breadcrumbItems" />

        @if (session('message'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-700 dark:bg-green-900/30 dark:text-green-200">
                {{ session('message') }}
            </div>
        @endif

        <div class="soh-card mb-6 overflow-hidden p-0">
            <div class="px-4 py-5 text-white sm:px-8 sm:py-7" style="background:#A6128D;">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-white/80 uppercase">Lesson Overview</p>
                        <h1 class="mt-2 text-2xl font-bold tracking-tight sm:text-3xl">{{ $lesson->title }}</h1>
                        <div class="mt-3 flex flex-wrap items-center gap-2 text-xs sm:text-sm">
                            <span class="rounded-full bg-white/15 px-3 py-1">
                                {{ $lesson->instrument ? ucfirst($lesson->instrument) : 'General' }}
                            </span>
                            <span class="rounded-full bg-white/15 px-3 py-1">
                                Teacher: {{ $lesson->teacher->name ?? 'N/A' }}
                            </span>
                            @if ($lesson->file_path)
                                <span class="rounded-full bg-white/15 px-3 py-1">Material Included</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('lessons.index') }}" class="rounded-md border border-white/35 bg-white/10 px-3 py-2 text-xs font-semibold text-white hover:bg-white/20 sm:text-sm">Back to Lessons</a>
                        @if ($lesson->file_path)
                            <a href="{{ route('lessons.download', $lesson) }}" class="rounded-md bg-white px-3 py-2 text-xs font-semibold sm:text-sm" style="color:#A6128D;">Download Material</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if (auth()->user()->hasRole('student'))
            @php
                $assignment = $lesson->assignedStudents->first();
            @endphp

            @if ($assignment)
                <div class="soh-card mb-6 border-l-4 p-4" style="border-left-color:#A6128D;">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <p class="text-sm text-gray-700 dark:text-gray-200">
                            Assignment status:
                            <strong class="ml-1">{{ ucfirst(str_replace('_', ' ', $assignment->status->value)) }}</strong>
                            @if ($assignment->due_date)
                                <span class="mx-1">•</span>Due {{ $assignment->due_date->format('M d, Y') }}
                            @endif
                        </p>

                        @if ($assignment->status->value === 'assigned')
                            <form method="POST" action="{{ route('lessons.mark-started', $lesson) }}">
                                @csrf
                                <button type="submit" class="rounded-md border px-3 py-1.5 text-xs font-semibold" style="border-color:#D991CD; background:#F2F2F2; color:#A6128D;">
                                    Mark as Started
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            @if ($assignment && $assignment->latestComment)
                <div class="soh-card mb-6 border-l-4 p-4" style="border-left-color:#A6128D;">
                    <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Private Teacher Note</p>
                    <p class="mt-2 text-sm text-gray-700">{{ $assignment->latestComment->body }}</p>
                </div>
            @elseif (! empty($lesson->global_note))
                <div class="soh-card mb-6 border-l-4 p-4" style="border-left-color:#A6128D;">
                    <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Global Lesson Note</p>
                    <p class="mt-2 text-sm text-gray-700">{{ $lesson->global_note }}</p>
                </div>
            @endif
        @elseif (auth()->user()->hasRole('parent'))
            @if ($lesson->assignedStudents->isNotEmpty())
                <div class="soh-card mb-6 border-l-4 p-4" style="border-left-color:#A6128D;">
                    <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Children’s Progress</p>
                    <div class="mt-3 space-y-3">
                        @foreach ($lesson->assignedStudents as $assignment)
                            <div class="rounded-lg border border-gray-200 bg-white p-4">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div>
                                        <div class="font-semibold text-black">{{ $assignment->student->name ?? 'Child' }}</div>
                                        <div class="text-sm text-gray-600">Status: {{ ucfirst(str_replace('_', ' ', $assignment->status->value)) }}</div>
                                    </div>
                                    @if ($assignment->due_date)
                                        <div class="text-xs font-medium" style="color:#A6128D;">Due {{ $assignment->due_date->format('M d, Y') }}</div>
                                    @endif
                                </div>
                                @if ($assignment->latestComment)
                                    <div class="mt-3 rounded-lg border border-gray-200 bg-[linear-gradient(180deg,#FFFFFF_0%,#FAF7FB_100%)] p-3 text-sm text-gray-700">
                                        <div class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Private Teacher Note</div>
                                        <p class="mt-2">{{ $assignment->latestComment->body }}</p>
                                    </div>
                                @elseif (! empty($lesson->global_note))
                                    <div class="mt-3 rounded-lg border border-gray-200 bg-[linear-gradient(180deg,#FFFFFF_0%,#FAF7FB_100%)] p-3 text-sm text-gray-700">
                                        <div class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Global Lesson Note</div>
                                        <p class="mt-2">{{ $lesson->global_note }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        <div class="soh-card p-4 sm:p-6">
            @if ($lesson->description)
                <div class="mb-5 rounded-lg border p-4" style="border-color:#D991CD; background:#F2F2F2;">
                    <p class="text-sm leading-relaxed text-gray-700 dark:text-gray-200">{{ $lesson->description }}</p>
                </div>
            @endif

            <h2 class="mb-3 text-lg font-semibold text-gray-900 dark:text-gray-100">Instructional Notes</h2>
            <div class="prose max-w-none whitespace-pre-line text-gray-800 dark:text-gray-100">
                {{ $lesson->content }}
            </div>
        </div>

        @if ($lesson->file_path)
            @php
                $extension = strtolower(pathinfo($lesson->file_path, PATHINFO_EXTENSION));
                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
                $audioExtensions = ['mp3', 'wav', 'ogg', 'm4a'];
                $videoExtensions = ['mp4', 'webm', 'ogg', 'mov'];
                $isPdf = $extension === 'pdf';
                $isImage = in_array($extension, $imageExtensions, true);
                $isAudio = in_array($extension, $audioExtensions, true);
                $isVideo = in_array($extension, $videoExtensions, true);
                $previewUrl = route('lessons.preview', $lesson);
            @endphp

            <div class="soh-card mt-6 p-4 sm:p-6">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Lesson Material Preview</h2>
                    <span class="rounded-full border border-gray-200 px-3 py-1 text-xs font-medium text-gray-600 dark:border-gray-700 dark:text-gray-300">
                        .{{ $extension }}
                    </span>
                </div>

                @if ($isPdf)
                    <iframe
                        src="{{ $previewUrl }}"
                        class="h-[52vh] w-full rounded-lg border border-gray-200 bg-white sm:h-[70vh]"
                        title="Lesson material preview"
                    ></iframe>
                @elseif ($isImage)
                    <img
                        src="{{ $previewUrl }}"
                        alt="Lesson material preview"
                        class="max-h-[52vh] w-full rounded-lg border border-gray-200 bg-white object-contain sm:max-h-[70vh]"
                    />
                @elseif ($isAudio)
                    <audio controls class="w-full rounded-md">
                        <source src="{{ $previewUrl }}" />
                        Your browser does not support audio preview.
                    </audio>
                @elseif ($isVideo)
                    <video controls class="max-h-[52vh] w-full rounded-lg border border-gray-200 bg-black sm:max-h-[70vh]">
                        <source src="{{ $previewUrl }}" />
                        Your browser does not support video preview.
                    </video>
                @else
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Preview is not available for this file type. Use Download Material to open it locally.
                    </p>
                @endif
            </div>
        @endif
    </div>
@endsection
