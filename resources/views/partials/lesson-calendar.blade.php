@props([
    'title' => 'Lesson Calendar',
    'description' => null,
    'eventsUrl',
    'viewerRole' => 'student',
    'collapsed' => true,
])

<section class="soh-card p-6">
    <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold text-black">{{ $title }}</h2>
            @if ($description)
                <p class="mt-1 text-sm text-gray-600">{{ $description }}</p>
            @endif
        </div>

        <button
            type="button"
            class="js-toggle-lesson-calendar soh-btn-outline"
            aria-expanded="{{ $collapsed ? 'false' : 'true' }}"
        >
            {{ $collapsed ? 'Show Calendar' : 'Hide Calendar' }}
        </button>
    </div>

    <div class="js-lesson-calendar-wrapper" data-events-url="{{ $eventsUrl }}" data-viewer-role="{{ $viewerRole }}">
        <div class="js-lesson-calendar-content {{ $collapsed ? 'hidden' : '' }}">
            <div class="js-lesson-calendar soh-calendar"></div>

            <div class="js-lesson-calendar-modal fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-3 sm:p-4">
                <div class="w-full max-w-lg rounded-2xl bg-white p-4 shadow-2xl sm:p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-black sm:text-lg">Lesson Details</h3>
                        <button type="button" class="js-close-lesson-calendar-modal rounded-md px-2 py-1 text-sm text-gray-500 hover:bg-gray-100">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>

                    <dl class="grid grid-cols-1 gap-3 text-sm text-gray-700 sm:grid-cols-2">
                        <div>
                            <dt class="font-semibold text-gray-900">Student</dt>
                            <dd class="js-event-student"></dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-gray-900">Teacher</dt>
                            <dd class="js-event-teacher"></dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-gray-900">Instrument</dt>
                            <dd class="js-event-instrument"></dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-gray-900">Date</dt>
                            <dd class="js-event-date"></dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-gray-900">Start</dt>
                            <dd class="js-event-start"></dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-gray-900">End</dt>
                            <dd class="js-event-end"></dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-gray-900">Duration</dt>
                            <dd class="js-event-duration"></dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-gray-900">Status</dt>
                            <dd class="js-event-status"></dd>
                        </div>
                    </dl>

                    <div class="js-event-student-note-wrap mt-4 hidden rounded-lg border border-gray-200 p-3">
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Student Note</p>
                        <p class="js-event-student-note mt-1 text-sm text-gray-700"></p>
                    </div>

                    <div class="js-event-teacher-note-wrap mt-3 hidden rounded-lg border border-gray-200 p-3">
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Teacher Note</p>
                        <p class="js-event-teacher-note mt-1 text-sm text-gray-700"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>