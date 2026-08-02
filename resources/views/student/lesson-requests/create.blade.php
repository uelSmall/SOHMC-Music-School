@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-5 sm:px-6 lg:px-8">
        <x-frontend.breadcrumbs :items="[
            ['label' => 'Student Dashboard', 'route' => route('student.dashboard')],
            ['label' => 'My Lesson Requests', 'route' => route('student.lesson-requests.index')],
            ['label' => 'Book a Lesson', 'current' => true],
        ]" />

        <div class="mb-6 flex flex-col gap-3">
            <a href="{{ route('student.lesson-requests.index') }}" class="soh-link text-sm">&larr; Back to My Lesson Requests</a>
            <div>
                <h1 class="soh-page-title">Book a Lesson</h1>
                <p class="soh-page-subtitle">Submit a lesson request with your preferred teacher, date, time, and duration.</p>
            </div>
        </div>

        <div class="soh-card p-6 md:p-8">
            <form method="POST" action="{{ route('student.lesson-requests.store') }}" class="space-y-6">
                @csrf

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <x-input-label for="instrument_id" value="Instrument" />
                        <select
                            id="instrument_id"
                            name="instrument_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--soh-purple)] focus:ring-[color:var(--soh-purple)]"
                        >
                            <option value="" disabled @selected(! old('instrument_id')) hidden>Select an instrument</option>
                            @foreach ($instruments as $instrument)
                                <option value="{{ $instrument->id }}" @selected(old('instrument_id') == $instrument->id)>{{ $instrument->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('instrument_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="teacher_id" value="Teacher" />
                        <select
                            id="teacher_id"
                            name="teacher_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--soh-purple)] focus:ring-[color:var(--soh-purple)]"
                        >
                            <option value="">Select an instrument</option>
                        </select>
                        <p class="mt-2 text-xs text-gray-500">Only teachers who teach the selected instrument will appear here.</p>
                        <x-input-error :messages="$errors->get('teacher_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="requested_date" value="Preferred Lesson Date" />
                        <x-text-input id="requested_date" name="requested_date" type="date" class="mt-1 block w-full" value="{{ old('requested_date') }}" />
                        <x-input-error :messages="$errors->get('requested_date')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="lesson_duration" value="Lesson Duration" />
                        <select id="lesson_duration" name="lesson_duration" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--soh-purple)] focus:ring-[color:var(--soh-purple)]">
                            <option value="">Select duration</option>
                            @foreach ([30, 45, 60] as $duration)
                                <option value="{{ $duration }}" @selected(old('lesson_duration') == $duration)>{{ $duration }} minutes</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('lesson_duration')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="requested_start_time" value="Preferred Start Time" />
                        <x-text-input id="requested_start_time" name="requested_start_time" type="time" class="mt-1 block w-full" value="{{ old('requested_start_time') }}" />
                        <x-input-error :messages="$errors->get('requested_start_time')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="requested_end_time" value="Preferred End Time" />
                        <x-text-input id="requested_end_time" name="requested_end_time" type="time" class="mt-1 block w-full" value="{{ old('requested_end_time') }}" />
                        <x-input-error :messages="$errors->get('requested_end_time')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="student_note" value="Optional Note" />
                    <textarea
                        id="student_note"
                        name="student_note"
                        rows="5"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--soh-purple)] focus:ring-[color:var(--soh-purple)]"
                        placeholder="For example: I prefer afternoon lessons."
                    >{{ old('student_note') }}</textarea>
                    <x-input-error :messages="$errors->get('student_note')" class="mt-2" />
                </div>

                <div class="flex flex-wrap items-center gap-3 border-t border-gray-200 pt-6">
                    <x-primary-button>
                        Submit Request
                    </x-primary-button>
                    <a href="{{ route('student.lesson-requests.index') }}" class="soh-btn-outline inline-flex items-center justify-center px-5 py-3">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    @php
        $teachersForJs = $teachers->map(function ($teacher) {
            return [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'instrumentIds' => $teacher->teachingInstruments->pluck('id')->values(),
            ];
        })->values();
    @endphp

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const instrumentSelect = document.getElementById('instrument_id');
            const teacherSelect = document.getElementById('teacher_id');
            const instrumentPlaceholder = instrumentSelect.querySelector('option[value=""]');
            const selectedTeacherId = @json(old('teacher_id'));
            const teachers = @json($teachersForJs);

            function hideInstrumentPlaceholderInList() {
                if (! instrumentPlaceholder) {
                    return;
                }

                instrumentPlaceholder.hidden = true;
            }

            function syncInstrumentPlaceholder() {
                if (! instrumentPlaceholder) {
                    return;
                }

                if (! instrumentSelect.value) {
                    instrumentPlaceholder.hidden = false;
                    instrumentPlaceholder.selected = true;
                } else {
                    instrumentPlaceholder.hidden = true;
                }
            }

            function renderTeachers() {
                const instrumentId = Number(instrumentSelect.value);

                teacherSelect.innerHTML = '';

                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = instrumentId ? 'Select a teacher' : 'Select an instrument';
                teacherSelect.appendChild(placeholder);

                if (! instrumentId) {
                    return;
                }

                const filteredTeachers = teachers.filter((teacher) => teacher.instrumentIds.map(Number).includes(instrumentId));

                filteredTeachers.forEach((teacher) => {
                    const option = document.createElement('option');
                    option.value = teacher.id;
                    option.textContent = teacher.name;
                    teacherSelect.appendChild(option);
                });

                if (selectedTeacherId && filteredTeachers.some((teacher) => String(teacher.id) === String(selectedTeacherId))) {
                    teacherSelect.value = selectedTeacherId;
                }

                if (! filteredTeachers.length) {
                    placeholder.textContent = 'No teachers available for this instrument';
                }
            }

            instrumentSelect.addEventListener('mousedown', hideInstrumentPlaceholderInList);
            instrumentSelect.addEventListener('touchstart', hideInstrumentPlaceholderInList, { passive: true });
            instrumentSelect.addEventListener('keydown', (event) => {
                if (['ArrowDown', 'ArrowUp', ' ', 'Enter'].includes(event.key)) {
                    hideInstrumentPlaceholderInList();
                }
            });

            instrumentSelect.addEventListener('blur', syncInstrumentPlaceholder);
            instrumentSelect.addEventListener('change', () => {
                syncInstrumentPlaceholder();
                renderTeachers();
            });

            syncInstrumentPlaceholder();
            renderTeachers();
        });
    </script>
@endsection