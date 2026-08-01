@extends('backend.layouts.app')

@section('title')
    @lang('Create Lesson')
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
            @lang('Create Lesson')
        </x-backend.breadcrumb-item>
    </x-backend.breadcrumbs>
@endsection

@section('content')
    <livewire:backend.lessons.lesson-form :route-prefix="$routePrefix" />
@endsection
