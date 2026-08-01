@extends('backend.layouts.app')

@section('title')
    {{ $lesson->title }}
@endsection

@section('breadcrumbs')
    <x-backend.breadcrumbs>
        <x-backend.breadcrumb-item route='{{ route("backend.dashboard") }}' icon="fa-solid fa-house">
            @lang('Dashboard')
        </x-backend.breadcrumb-item>
        <x-backend.breadcrumb-item route='{{ route("backend.lessons.index") }}' icon="{{ $module_icon }}">
            @lang('Lessons')
        </x-backend.breadcrumb-item>
        <x-backend.breadcrumb-item type="active" icon="{{ $module_icon }}">
            {{ $lesson->title }}
        </x-backend.breadcrumb-item>
    </x-backend.breadcrumbs>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
                <div>
                    <h3 class="mb-1">{{ $lesson->title }}</h3>
                    <p class="text-muted mb-0">{{ $lesson->description }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route($routePrefix.'.lessons.edit', $lesson) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route($routePrefix.'.lessons.index') }}" class="btn btn-outline-secondary">Back</a>
                </div>
            </div>

            <div class="mb-3">
                <strong>Teacher:</strong>
                <span>{{ $lesson->teacher?->name ?? 'Unassigned' }}</span>
            </div>

            @if ($lesson->file_path)
                <div class="mb-3">
                    <strong>Material:</strong>
                    <a href="{{ Storage::url($lesson->file_path) }}" target="_blank" class="ms-2">View File</a>
                </div>
            @endif

            <div>
                <strong>Content</strong>
                <div class="mt-2 p-3 border rounded bg-light" style="white-space: pre-line;">{{ $lesson->content }}</div>
            </div>
        </div>
    </div>
@endsection
