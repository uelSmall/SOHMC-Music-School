{{--
    Card action buttons for a booking item (BookedLesson or LessonRequest).
    Expects a variable named $item. Includes View + Delete; the Delete form
    hard-deletes the booking (and its linked lesson via FK cascade).
--}}
@php
    $isRequest = $item instanceof \Modules\Booking\Models\LessonRequest;
    $studentName = $item->student?->name ?? 'this student';
    $variant = $variant ?? 'solid';
    $confirm = 'Delete this '.($isRequest ? 'booking request' : 'booking')." for {$studentName}? This permanently removes it. There is no undo.";
@endphp
<div class="flex items-center gap-2">
    <a href="{{ route('bookings.show', $item) }}" class="soh-link text-sm font-medium whitespace-nowrap">View</a>
    <form method="POST" action="{{ $isRequest ? route('bookings.destroy-request', $item) : route('bookings.destroy-lesson', $item) }}" onsubmit='return confirm(@js($confirm))'>
        @csrf
        @method('DELETE')
        <button type="submit" class="{{ $variant === 'outline' ? 'rounded-lg px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50' : 'rounded-md bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700' }}">Delete</button>
    </form>
</div>