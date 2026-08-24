@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-3xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        <x-frontend.breadcrumbs :items="[
            ['label' => 'Student Dashboard', 'route' => route('student.dashboard')],
            ['label' => 'My Bookings', 'route' => route('bookings.index')],
            ['label' => 'Book a Lesson', 'current' => true],
        ]" />

        <div class="flex flex-col gap-3">
            <a href="{{ route('bookings.index') }}" class="soh-link text-sm">&larr; Back to Bookings</a>
            <div>
                <h1 class="soh-page-title">Book a Lesson</h1>
                <p class="soh-page-subtitle">Request a lesson with one of our teachers. They&apos;ll review and confirm your schedule.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('bookings.store') }}" class="soh-card space-y-6 p-6">
            @csrf

            <div>
                <x-input-label for="instrument_id" value="Instrument *" />
                <select id="instrument_id" name="instrument_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--soh-purple)] focus:ring-[color:var(--soh-purple)]" required>
                    <option value="">Select an instrument</option>
                    @foreach($instruments as $instrument)
                        <option value="{{ $instrument->id }}" {{ old('instrument_id') == $instrument->id ? 'selected' : '' }}>{{ $instrument->name }}</option>
                    @endforeach
                </select>
                @error('instrument_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <x-input-label for="teacher_id" value="Teacher *" />
                <select id="teacher_id" name="teacher_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--soh-purple)] focus:ring-[color:var(--soh-purple)]" required>
                    <option value="">Select a teacher</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                    @endforeach
                </select>
                @error('teacher_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <x-input-label for="requested_date" value="Preferred Date *" />
                <x-text-input id="requested_date" name="requested_date" type="date" class="mt-1 block w-full" value="{{ old('requested_date') }}" required />
                @error('requested_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="requested_start_time" value="Start Time *" />
                    <x-text-input id="requested_start_time" name="requested_start_time" type="time" class="mt-1 block w-full" value="{{ old('requested_start_time') }}" required />
                    @error('requested_start_time') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <x-input-label for="requested_end_time" value="End Time *" />
                    <x-text-input id="requested_end_time" name="requested_end_time" type="time" class="mt-1 block w-full" value="{{ old('requested_end_time') }}" required />
                    @error('requested_end_time') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <x-input-label for="lesson_duration" value="Duration (minutes) *" />
                <select id="lesson_duration" name="lesson_duration" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--soh-purple)] focus:ring-[color:var(--soh-purple)]" required>
                    @foreach([15, 30, 45, 60, 90, 120] as $minutes)
                        <option value="{{ $minutes }}" {{ old('lesson_duration', 60) == $minutes ? 'selected' : '' }}>{{ $minutes }} minutes</option>
                    @endforeach
                </select>
                @error('lesson_duration') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <x-input-label for="student_note" value="Message (Optional)" />
                <textarea id="student_note" name="student_note" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--soh-purple)] focus:ring-[color:var(--soh-purple)]" placeholder="Any special requests, skill level, or notes for the teacher...">{{ old('student_note') }}</textarea>
                @error('student_note') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('bookings.index') }}" class="soh-btn-outline">Cancel</a>
                <button type="submit" class="soh-btn-primary">Submit Request</button>
            </div>
        </form>
    </div>
@endsection
