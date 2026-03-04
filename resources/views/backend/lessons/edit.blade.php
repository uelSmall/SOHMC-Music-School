@extends('backend.layouts.app')

@section('title')
    @lang('Edit Lesson')
@endsection

@section('breadcrumbs')
    <x-backend.breadcrumbs />
@endsection

@section('content')
    <livewire:backend.lessons.lesson-form :lesson="$lesson" />
@endsection
