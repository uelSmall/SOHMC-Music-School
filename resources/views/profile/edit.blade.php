@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h1 class="text-2xl font-semibold mb-6">Edit Profile</h1>

    @if(session('status'))
        <div class="mb-4 text-sm text-green-600">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input name="name" type="text" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input name="email" type="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
        </div>
    </form>
</div>
@endsection
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __("Profile") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="bg-white p-4 shadow-sm dark:bg-gray-800 sm:rounded-lg sm:p-8">
                <div class="max-w-xl">
                    @include("profile.partials.update-profile-information-form")
                </div>
            </div>

            <div class="bg-white p-4 shadow-sm dark:bg-gray-800 sm:rounded-lg sm:p-8">
                <div class="max-w-xl">
                    @include("profile.partials.update-password-form")
                </div>
            </div>

            <div class="bg-white p-4 shadow-sm dark:bg-gray-800 sm:rounded-lg sm:p-8">
                <div class="max-w-xl">
                    @include("profile.partials.delete-user-form")
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
