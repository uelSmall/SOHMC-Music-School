@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="soh-page-title">My Lessons</h1>
        <p class="soh-page-subtitle">Browse lessons, filter quickly, and manage assignment progress.</p>
    </div>

    <livewire:frontend.lessons.lesson-search />
</div>
@endsection
