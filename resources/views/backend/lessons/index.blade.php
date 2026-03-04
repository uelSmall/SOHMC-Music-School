@extends('backend.layouts.app')

@section('title')
    @lang('Lessons')
@endsection

@section('breadcrumbs')
    <x-backend.breadcrumbs />
@endsection

@section('content')
    <livewire:backend.lessons.lesson-list />
@endsection
