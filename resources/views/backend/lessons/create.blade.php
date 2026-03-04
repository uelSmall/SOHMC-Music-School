@extends('backend.layouts.app')

@section('title')
    @lang('Create Lesson')
@endsection

@section('breadcrumbs')
    <x-backend.breadcrumbs />
@endsection

@section('content')
    <livewire:backend.lessons.lesson-form :route-prefix="$routePrefix" />
@endsection
