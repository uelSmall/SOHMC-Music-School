@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Lessons</h1>
        <p class="text-gray-600 mt-2">Browse, search, and manage your lesson assignments.</p>
    </div>

    <livewire:frontend.lessons.lesson-search />
</div>
@endsection
