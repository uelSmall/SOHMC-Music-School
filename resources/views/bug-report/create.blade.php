@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-3xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        <x-frontend.breadcrumbs :items="[
            ['label' => str(Auth::user()->dashboardRouteName())->before('.')->headline() . ' Dashboard', 'route' => route(Auth::user()->dashboardRouteName())],
            ['label' => 'Report a Bug', 'current' => true],
        ]" />

        <div>
            <h1 class="soh-page-title">Report a Bug</h1>
            <p class="soh-page-subtitle">Found something that isn&apos;t working right? Let us know and we&apos;ll get it fixed.</p>
        </div>

        @if(session('flash_success'))
            <div class="flex items-start gap-3 rounded-2xl border border-green-200 bg-green-50 p-4">
                <svg class="h-5 w-5 shrink-0 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <div>
                    <p class="font-semibold text-green-800">Thanks for reporting this!</p>
                    <p class="text-sm text-green-700">Our team has been notified and will look into it.</p>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('bug-reports.store') }}" class="soh-card space-y-6 p-6">
            @csrf

            <div>
                <x-input-label for="title" value="What went wrong? *" />
                <input type="text" id="title" name="title" value="{{ old('title') }}" required maxlength="191" placeholder="e.g. The upload button does nothing when clicked"
                       class="mt-1 block w-full rounded-xl border border-[color:var(--soh-gray)]/40 bg-[color:var(--soh-surface)] px-4 py-3 text-sm outline-none transition focus:border-[color:var(--soh-purple)] focus:bg-white focus:ring-2 focus:ring-[color:var(--soh-purple)]/20" />
                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <x-input-label for="description" value="Describe the problem *" />
                <textarea id="description" name="description" rows="6" required maxlength="5000" placeholder="Please include what you expected to happen and what actually happened."
                          class="mt-1 block w-full rounded-xl border border-[color:var(--soh-gray)]/40 bg-[color:var(--soh-surface)] px-4 py-3 text-sm outline-none transition focus:border-[color:var(--soh-purple)] focus:bg-white focus:ring-2 focus:ring-[color:var(--soh-purple)]/20"></textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <x-input-label for="page" value="Where did it happen? (optional)" />
                <input type="text" id="page" name="page" value="{{ old('page') }}" maxlength="500" placeholder="e.g. /bookings or the dashboard"
                       class="mt-1 block w-full rounded-xl border border-[color:var(--soh-gray)]/40 bg-[color:var(--soh-surface)] px-4 py-3 text-sm outline-none transition focus:border-[color:var(--soh-purple)] focus:bg-white focus:ring-2 focus:ring-[color:var(--soh-purple)]/20" />
                @error('page') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="rounded-xl bg-[color:var(--soh-surface)] p-4 text-sm text-gray-600">
                <div class="flex items-start gap-3">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-[color:var(--soh-purple)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="font-semibold text-gray-800">Helpful hints</p>
                        <p class="mt-1">We automatically capture your browser and the page you were on when submitting. Be specific so we can reproduce the issue quickly.</p>
                    </div>
                </div>
            </div>

            <button type="submit" class="soh-btn-primary w-full justify-center">
                Submit Bug Report
            </button>
        </form>
    </div>
@endsection