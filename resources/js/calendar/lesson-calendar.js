import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

const MOBILE_BREAKPOINT = 768;

function isMobile() {
    return window.innerWidth < MOBILE_BREAKPOINT;
}

function getHeaderToolbar() {
    if (isMobile()) {
        return {
            left: 'prev,next',
            center: 'title',
            right: 'timeGridDay,timeGridWeek',
        };
    }

    return {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay',
    };
}

function getInitialView() {
    return isMobile() ? 'timeGridDay' : 'dayGridMonth';
}

function textOrFallback(value, fallback = 'N/A') {
    return value && String(value).trim().length > 0 ? value : fallback;
}

function populateAndOpenModal(wrapper, eventData) {
    const modal = wrapper.querySelector('.js-lesson-calendar-modal');

    if (!modal) {
        return;
    }

    const props = eventData.extendedProps ?? {};

    modal.querySelector('.js-event-student').textContent = textOrFallback(props.student);
    modal.querySelector('.js-event-teacher').textContent = textOrFallback(props.teacher);
    modal.querySelector('.js-event-instrument').textContent = textOrFallback(props.instrument);
    modal.querySelector('.js-event-date').textContent = textOrFallback(props.date);
    modal.querySelector('.js-event-start').textContent = textOrFallback(props.start_time);
    modal.querySelector('.js-event-end').textContent = textOrFallback(props.end_time);
    modal.querySelector('.js-event-duration').textContent = `${textOrFallback(props.duration, 0)} minutes`;
    modal.querySelector('.js-event-status').textContent = textOrFallback(props.status_label, textOrFallback(props.status));

    const studentNoteWrap = modal.querySelector('.js-event-student-note-wrap');
    const teacherNoteWrap = modal.querySelector('.js-event-teacher-note-wrap');
    const studentNote = textOrFallback(props.student_note, '');
    const teacherNote = textOrFallback(props.teacher_note, '');

    if (studentNote) {
        studentNoteWrap.classList.remove('hidden');
        modal.querySelector('.js-event-student-note').textContent = studentNote;
    } else {
        studentNoteWrap.classList.add('hidden');
    }

    if (teacherNote) {
        teacherNoteWrap.classList.remove('hidden');
        modal.querySelector('.js-event-teacher-note').textContent = teacherNote;
    } else {
        teacherNoteWrap.classList.add('hidden');
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function bindModalCloseHandlers(wrapper) {
    const modal = wrapper.querySelector('.js-lesson-calendar-modal');

    if (!modal || modal.dataset.bound === '1') {
        return;
    }

    const closeButton = modal.querySelector('.js-close-lesson-calendar-modal');

    closeButton?.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });

    modal.dataset.bound = '1';
}

function initCalendarWrapper(wrapper) {
    if (wrapper.dataset.initialized === '1') {
        return wrapper.__calendarInstance;
    }

    const eventsUrl = wrapper.dataset.eventsUrl;
    const calendarElement = wrapper.querySelector('.js-lesson-calendar');

    if (!eventsUrl || !calendarElement) {
        return;
    }

    bindModalCloseHandlers(wrapper);

    const calendar = new Calendar(calendarElement, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: getInitialView(),
        height: 'auto',
        contentHeight: isMobile() ? 400 : 'auto',
        headerToolbar: getHeaderToolbar(),
        slotMinTime: '07:00:00',
        slotMaxTime: '21:00:00',
        slotDuration: '00:30:00',
        allDaySlot: false,
        eventTimeFormat: {
            hour: 'numeric',
            minute: '2-digit',
            meridiem: 'short',
        },
        dayMaxEvents: isMobile() ? 3 : false,
        events(fetchInfo, successCallback, failureCallback) {
            const params = new URLSearchParams({
                start: fetchInfo.startStr,
                end: fetchInfo.endStr,
            });

            fetch(`${eventsUrl}?${params.toString()}`, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Unable to load calendar events.');
                    }

                    return response.json();
                })
                .then((events) => successCallback(events))
                .catch((error) => failureCallback(error));
        },
        eventClick(eventInfo) {
            eventInfo.jsEvent.preventDefault();
            populateAndOpenModal(wrapper, eventInfo.event);
        },
    });

    calendar.render();
    wrapper.dataset.initialized = '1';
    wrapper.__calendarInstance = calendar;

    return calendar;
}

function bindCalendarToggle(wrapper) {
    if (wrapper.dataset.toggleBound === '1') {
        return;
    }

    const toggleButton = wrapper.closest('section')?.querySelector('.js-toggle-lesson-calendar');
    const content = wrapper.querySelector('.js-lesson-calendar-content');

    if (!toggleButton || !content) {
        return;
    }

    const setButtonState = (isOpen) => {
        toggleButton.textContent = isOpen ? 'Hide Calendar' : 'Show Calendar';
        toggleButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    };

    toggleButton.addEventListener('click', () => {
        const willOpen = content.classList.contains('hidden');

        content.classList.toggle('hidden', !willOpen);
        setButtonState(willOpen);

        if (willOpen) {
            const calendar = initCalendarWrapper(wrapper);

            if (calendar) {
                setTimeout(() => {
                    calendar.updateSize();
                    calendar.refetchEvents();
                }, 100);
            }
        }
    });

    const isOpenByDefault = !content.classList.contains('hidden');
    setButtonState(isOpenByDefault);

    if (isOpenByDefault) {
        initCalendarWrapper(wrapper);
    }

    wrapper.dataset.toggleBound = '1';
}

let resizeTimer;

window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        document.querySelectorAll('.js-lesson-calendar-wrapper').forEach((wrapper) => {
            const calendar = wrapper.__calendarInstance;

            if (calendar) {
                calendar.setOption('headerToolbar', getHeaderToolbar());
                calendar.setOption('height', 'auto');
                calendar.setOption('contentHeight', isMobile() ? 400 : 'auto');
                calendar.setOption('dayMaxEvents', isMobile() ? 3 : false);
                calendar.updateSize();
            }
        });
    }, 250);
});

export function initializeLessonCalendars() {
    document.querySelectorAll('.js-lesson-calendar-wrapper').forEach((wrapper) => {
        bindCalendarToggle(wrapper);

        const content = wrapper.querySelector('.js-lesson-calendar-content');

        if (content && !content.classList.contains('hidden')) {
            initCalendarWrapper(wrapper);
        }
    });
}
