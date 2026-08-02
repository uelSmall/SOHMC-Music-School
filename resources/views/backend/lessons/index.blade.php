@extends('backend.layouts.app')

@section('title')
    @lang('Lessons')
@endsection

@section('breadcrumbs')
    <x-backend.breadcrumbs>
        <x-backend.breadcrumb-item route='{{ route("backend.dashboard") }}' icon="fa-solid fa-house">
            @lang('Dashboard')
        </x-backend.breadcrumb-item>
        <x-backend.breadcrumb-item type="active" icon="{{ $module_icon }}">
            @lang('Lessons')
        </x-backend.breadcrumb-item>
    </x-backend.breadcrumbs>
@endsection

@section('content')
    <livewire:backend.lessons.lesson-list :route-prefix="$routePrefix" />
@endsection
