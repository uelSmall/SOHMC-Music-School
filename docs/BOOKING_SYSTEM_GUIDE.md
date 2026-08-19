# Booking System Complete Guide

This guide provides comprehensive documentation for the SOHMC Music School's dynamic booking and scheduling system, including lesson requests, teacher availability, calendar integration, and the complete booking lifecycle.

## 🎯 Overview

The booking system is a comprehensive lesson scheduling solution that allows students to request lessons with their preferred times, teachers to manage their availability and respond to requests, and both parties to manage scheduled lessons through a visual calendar interface.

### Key Features
- **Lesson Requests**: Students submit requests with preferred times and instruments
- **Teacher Availability**: Teachers set weekly availability windows
- **Booking Workflow**: Complex state machine for request processing
- **Teacher Suggestions**: Teachers can suggest alternative times
- **Calendar Integration**: FullCalendar for visual scheduling
- **Lifecycle Management**: Complete tracking of booking changes
- **Notification System**: Real-time alerts for booking events

---

## 📊 Database Architecture

### Core Tables

#### `instruments` Table
```sql
CREATE TABLE instruments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Purpose**: Normalized instrument management for the booking system.

**Key Features**:
- Active/inactive states for instrument availability
- Descriptions for instrument details
- Used in lesson requests and teacher assignments

#### `instrument_teacher` Table
```sql
CREATE TABLE instrument_teacher (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    instrument_id BIGINT NOT NULL,
    teacher_id BIGINT NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (instrument_id) REFERENCES instruments(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY (instrument_id, teacher_id)
);
```

**Purpose**: Many-to-many relationship between teachers and instruments.

**Key Features**:
- Teachers can teach multiple instruments
- Instruments can have multiple teachers
- Cascade delete for data integrity

#### `teacher_availability` Table
```sql
CREATE TABLE teacher_availability (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    teacher_id BIGINT NOT NULL,
    day_of_week ENUM('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
);
```

**Purpose**: Define when teachers are available for lessons.

**Key Features**:
- Weekly availability windows
- Day-specific scheduling
- Time range validation
- Active/inactive for temporary changes

#### `lesson_requests` Table
```sql
CREATE TABLE lesson_requests (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    student_id BIGINT NOT NULL,
    teacher_id BIGINT NOT NULL,
    instrument_id BIGINT NOT NULL,
    requested_date DATE NOT NULL,
    requested_start_time TIME NOT NULL,
    requested_end_time TIME NOT NULL,
    lesson_duration INT DEFAULT 60,
    suggested_date DATE NULL,
    suggested_start_time TIME NULL,
    suggested_end_time TIME NULL,
    status ENUM('pending','teacher_confirmed','teacher_rescheduled','student_accepted','student_declined','cancelled') DEFAULT 'pending',
    student_note TEXT NULL,
    teacher_note TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (instrument_id) REFERENCES instruments(id) ON DELETE CASCADE
);
```

**Purpose**: Track lesson requests through the approval workflow.

**Key Features**:
- Student's preferred time (requested_date, requested_start_time, requested_end_time)
- Teacher's suggested alternative (suggested_date, suggested_start_time, suggested_end_time)
- Status workflow management
- Notes for communication between parties

#### `booked_lessons` Table
```sql
CREATE TABLE booked_lessons (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    lesson_request_id BIGINT NULL,
    student_id BIGINT NOT NULL,
    teacher_id BIGINT NOT NULL,
    instrument_id BIGINT NOT NULL,
    lesson_date DATE NOT NULL,
    lesson_start_time TIME NOT NULL,
    lesson_end_time TIME NOT NULL,
    lesson_duration INT DEFAULT 60,
    status ENUM('scheduled','completed','cancelled') DEFAULT 'scheduled',
    completed_at TIMESTAMP NULL,
    cancelled_at TIMESTAMP NULL,
    rescheduled_at TIMESTAMP NULL,
    cancellation_reason TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (lesson_request_id) REFERENCES lesson_requests(id) ON DELETE SET NULL,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (instrument_id) REFERENCES instruments(id) ON DELETE CASCADE
);
```

**Purpose**: Store confirmed/scheduled lessons with full lifecycle tracking.

**Key Features**:
- Links to original lesson request
- Scheduled lesson details
- Status tracking (scheduled/completed/cancelled)
- Lifecycle timestamps (completed_at, cancelled_at, rescheduled_at)
- Cancellation reasons for audit trail

---

## 🔧 Models & Relationships

### Instrument Model
**File**: `Modules/Booking/Models/Instrument.php`

```php
class Instrument extends Model
{
    protected $fillable = ['name', 'description', 'is_active'];
    
    protected function casts(): array
    {
        return ['is_active' => 'bool'];
    }
    
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'instrument_teacher', 'instrument_id', 'teacher_id')
            ->withTimestamps();
    }
    
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
```

**Key Relationships**:
- `teachers()`: Many-to-many relationship with User model

**Key Methods**:
- `scopeActive()`: Filter for active instruments only

### LessonRequest Model
**File**: `Modules/Booking/Models/LessonRequest.php`

```php
class LessonRequest extends Model
{
    protected $fillable = [
        'student_id', 'teacher_id', 'instrument_id',
        'requested_date', 'requested_start_time', 'requested_end_time',
        'lesson_duration', 'suggested_date', 'suggested_start_time', 'suggested_end_time',
        'status', 'student_note', 'teacher_note'
    ];
    
    protected function casts(): array
    {
        return [
            'requested_date' => 'date',
            'suggested_date' => 'date',
            'lesson_duration' => 'integer',
            'status' => LessonRequestStatus::class,
        ];
    }
    
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    
    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class, 'instrument_id');
    }
    
    public function lesson(): HasOne
    {
        return $this->hasOne(BookedLesson::class, 'lesson_request_id');
    }
}
```

**Key Relationships**:
- `student()`: The student who made the request
- `teacher()`: The teacher receiving the request
- `instrument()`: The requested instrument
- `lesson()`: The resulting booked lesson (if confirmed)

### BookedLesson Model
**File**: `Modules/Booking/Models/BookedLesson.php`

```php
class BookedLesson extends Model
{
    protected $fillable = [
        'lesson_request_id', 'student_id', 'teacher_id', 'instrument_id',
        'lesson_date', 'lesson_start_time', 'lesson_end_time', 'lesson_duration',
        'status', 'completed_at', 'cancelled_at', 'rescheduled_at', 'cancellation_reason'
    ];
    
    protected function casts(): array
    {
        return [
            'lesson_date' => 'date',
            'lesson_duration' => 'integer',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'rescheduled_at' => 'datetime',
            'status' => LessonStatus::class,
        ];
    }
    
    public function lessonRequest(): BelongsTo
    {
        return $this->belongsTo(LessonRequest::class, 'lesson_request_id');
    }
    
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    
    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class, 'instrument_id');
    }
}
```

**Key Relationships**:
- `lessonRequest()`: Original request that led to this booking
- `student()`: Student attending the lesson
- `teacher()`: Teacher conducting the lesson
- `instrument()`: Instrument for the lesson

### TeacherAvailability Model
**File**: `Modules/Booking/Models/TeacherAvailability.php`

```php
class TeacherAvailability extends Model
{
    protected $fillable = ['teacher_id', 'day_of_week', 'start_time', 'end_time', 'is_active'];
    
    protected function casts(): array
    {
        return ['is_active' => 'bool'];
    }
    
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
```

**Key Relationships**:
- `teacher()`: The teacher with this availability

---

## 🔄 Enums & State Machines

### LessonRequestStatus Enum
**File**: `Modules/Booking/Enums/LessonRequestStatus.php`

```php
enum LessonRequestStatus: string
{
    case Pending = 'pending';
    case TeacherConfirmed = 'teacher_confirmed';
    case TeacherRescheduled = 'teacher_rescheduled';
    case StudentAccepted = 'student_accepted';
    case StudentDeclined = 'student_declined';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::TeacherConfirmed => 'Teacher Confirmed',
            self::TeacherRescheduled => 'Teacher Rescheduled',
            self::StudentAccepted => 'Student Accepted',
            self::StudentDeclined => 'Student Declined',
            self::Cancelled => 'Cancelled',
        };
    }
}
```

**Status Flow**:
1. **Pending** → Initial state when student submits request
2. **TeacherConfirmed** → Teacher confirms the requested time
3. **TeacherRescheduled** → Teacher suggests alternative time
4. **StudentAccepted** → Student accepts (confirmed or suggested) time
5. **StudentDeclined** → Student declines the suggested time
6. **Cancelled** → Request cancelled by either party

### LessonStatus Enum (Booking)
**File**: `Modules/Booking/Enums/LessonStatus.php`

```php
enum LessonStatus: string
{
    case Scheduled = 'scheduled';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
```

**Status Flow**:
1. **Scheduled** → Initial state when lesson is booked
2. **Completed** → Lesson has been conducted
3. **Cancelled** → Lesson was cancelled

---

## 🎮 Controllers & Workflows

### Student Lesson Request Controller
**File**: `app/Http/Controllers/Student/LessonRequestController.php`

#### Creating a Lesson Request
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'teacher_id' => 'required|exists:users,id',
        'instrument_id' => 'required|exists:instruments,id',
        'requested_date' => 'required|date|after:today',
        'requested_start_time' => 'required|date_format:H:i',
        'requested_end_time' => 'required|date_format:H:i|after:requested_start_time',
        'lesson_duration' => 'required|integer|in:30,45,60,90',
        'student_note' => 'nullable|string|max:500',
    ]);

    // Check teacher availability for requested time
    $this->validateTeacherAvailability(
        $validated['teacher_id'],
        $validated['requested_date'],
        $validated['requested_start_time'],
        $validated['requested_end_time']
    );

    $lessonRequest = LessonRequest::create([
        'student_id' => auth()->id(),
        'teacher_id' => $validated['teacher_id'],
        'instrument_id' => $validated['instrument_id'],
        'requested_date' => $validated['requested_date'],
        'requested_start_time' => $validated['requested_start_time'],
        'requested_end_time' => $validated['requested_end_time'],
        'lesson_duration' => $validated['lesson_duration'],
        'student_note' => $validated['student_note'] ?? null,
        'status' => LessonRequestStatus::Pending,
    ]);

    // Notify teacher of new request
    $lessonRequest->teacher->notify(new NewLessonRequestNotification($lessonRequest));

    return redirect()->back()->with('success', 'Lesson request submitted successfully.');
}
```

#### Accepting Teacher Suggestion
```php
public function acceptSuggestion(LessonRequest $lessonRequest)
{
    abort_unless($lessonRequest->student_id === auth()->id(), 403);
    abort_unless($lessonRequest->status === LessonRequestStatus::TeacherRescheduled, 403);

    // Create booked lesson from suggestion
    $bookedLesson = BookedLesson::create([
        'lesson_request_id' => $lessonRequest->id,
        'student_id' => $lessonRequest->student_id,
        'teacher_id' => $lessonRequest->teacher_id,
        'instrument_id' => $lessonRequest->instrument_id,
        'lesson_date' => $lessonRequest->suggested_date,
        'lesson_start_time' => $lessonRequest->suggested_start_time,
        'lesson_end_time' => $lessonRequest->suggested_end_time,
        'lesson_duration' => $lessonRequest->lesson_duration,
        'status' => LessonStatus::Scheduled,
    ]);

    // Update request status
    $lessonRequest->update(['status' => LessonRequestStatus::StudentAccepted]);

    // Notify teacher
    $lessonRequest->teacher->notify(new LessonSuggestionAcceptedNotification($lessonRequest));

    return redirect()->back()->with('success', 'Lesson time accepted. Your lesson is scheduled!');
}
```

### Teacher Lesson Request Controller
**File**: `app/Http/Controllers/Teacher/LessonRequestController.php`

#### Confirming a Lesson Request
```php
public function confirm(LessonRequest $lessonRequest, ConfirmLessonRequest $request)
{
    abort_unless($lessonRequest->teacher_id === auth()->id(), 403);
    abort_unless($lessonRequest->status === LessonRequestStatus::Pending, 403);

    // Create booked lesson
    $bookedLesson = BookedLesson::create([
        'lesson_request_id' => $lessonRequest->id,
        'student_id' => $lessonRequest->student_id,
        'teacher_id' => $lessonRequest->teacher_id,
        'instrument_id' => $lessonRequest->instrument_id,
        'lesson_date' => $lessonRequest->requested_date,
        'lesson_start_time' => $lessonRequest->requested_start_time,
        'lesson_end_time' => $lessonRequest->requested_end_time,
        'lesson_duration' => $lessonRequest->lesson_duration,
        'status' => LessonStatus::Scheduled,
    ]);

    // Update request status
    $lessonRequest->update(['status' => LessonRequestStatus::TeacherConfirmed]);

    // Notify student
    $lessonRequest->student->notify(new LessonConfirmedNotification($lessonRequest, $bookedLesson));

    return redirect()->back()->with('success', 'Lesson confirmed and scheduled successfully.');
}
```

#### Suggesting Alternative Time
```php
public function reschedule(LessonRequest $lessonRequest, RescheduleLessonRequest $request)
{
    abort_unless($lessonRequest->teacher_id === auth()->id(), 403);
    abort_unless($lessonRequest->status === LessonRequestStatus::Pending, 403);

    $validated = $request->validated();

    // Update request with teacher's suggestion
    $lessonRequest->update([
        'suggested_date' => $validated['suggested_date'],
        'suggested_start_time' => $validated['suggested_start_time'],
        'suggested_end_time' => $validated['suggested_end_time'],
        'teacher_note' => $validated['teacher_note'] ?? null,
        'status' => LessonRequestStatus::TeacherRescheduled,
    ]);

    // Notify student of suggestion
    $lessonRequest->student->notify(new LessonRescheduledNotification($lessonRequest));

    return redirect()->back()->with('success', 'Alternative time suggested to student.');
}
```

#### Rejecting a Lesson Request
```php
public function reject(LessonRequest $lessonRequest, RejectLessonRequest $request)
{
    abort_unless($lessonRequest->teacher_id === auth()->id(), 403);
    abort_unless($lessonRequest->status === LessonRequestStatus::Pending, 403);

    $validated = $request->validated();

    // Update request status
    $lessonRequest->update([
        'teacher_note' => $validated['teacher_note'] ?? null,
        'status' => LessonRequestStatus::Cancelled,
    ]);

    // Notify student of rejection
    $lessonRequest->student->notify(new LessonRejectedNotification($lessonRequest));

    return redirect()->back()->with('success', 'Lesson request rejected.');
}
```

### Teacher Lesson Management Controller
**File**: `app/Http/Controllers/Teacher/LessonManagementController.php`

#### Completing a Booked Lesson
```php
public function complete(BookedLesson $bookedLesson, CompleteLessonRequest $request)
{
    abort_unless($bookedLesson->teacher_id === auth()->id(), 403);
    abort_unless($bookedLesson->status === LessonStatus::Scheduled, 403);

    $bookedLesson->update([
        'status' => LessonStatus::Completed,
        'completed_at' => now(),
    ]);

    // Notify student
    $bookedLesson->student->notify(new LessonLifecycleNotification($bookedLesson, 'completed'));

    return redirect()->back()->with('success', 'Lesson marked as completed.');
}
```

#### Cancelling a Booked Lesson
```php
public function cancel(BookedLesson $bookedLesson, CancelLessonRequest $request)
{
    abort_unless($bookedLesson->teacher_id === auth()->id(), 403);
    abort_unless($bookedLesson->status === LessonStatus::Scheduled, 403);

    $validated = $request->validated();

    $bookedLesson->update([
        'status' => LessonStatus::Cancelled,
        'cancelled_at' => now(),
        'cancellation_reason' => $validated['cancellation_reason'] ?? null,
    ]);

    // Notify student
    $bookedLesson->student->notify(new BookedLessonCancelledNotification($bookedLesson));

    return redirect()->back()->with('success', 'Lesson cancelled successfully.');
}
```

#### Rescheduling a Booked Lesson
```php
public function reschedule(BookedLesson $bookedLesson, RescheduleBookedLessonRequest $request)
{
    abort_unless($bookedLesson->teacher_id === auth()->id(), 403);
    abort_unless($bookedLesson->status === LessonStatus::Scheduled, 403);

    $validated = $request->validated();

    $bookedLesson->update([
        'lesson_date' => $validated['new_date'],
        'lesson_start_time' => $validated['new_start_time'],
        'lesson_end_time' => $validated['new_end_time'],
        'rescheduled_at' => now(),
    ]);

    // Notify student
    $bookedLesson->student->notify(new BookedLessonRescheduledNotification($bookedLesson));

    return redirect()->back()->with('success', 'Lesson rescheduled successfully.');
}
```

### Calendar Controller
**File**: `app/Http/Controllers/Calendar/LessonCalendarController.php`

#### Teacher Calendar Events
```php
public function teacherEvents(Request $request)
{
    $teacherId = auth()->id();
    $start = $request->query('start');
    $end = $request->query('end');

    // Get scheduled lessons
    $lessons = BookedLesson::where('teacher_id', $teacherId)
        ->whereBetween('lesson_date', [$start, $end])
        ->where('status', LessonStatus::Scheduled)
        ->with(['student:id,name', 'instrument:id,name'])
        ->get()
        ->map(function ($lesson) {
            return [
                'id' => $lesson->id,
                'title' => "{$lesson->student->name} - {$lesson->instrument->name}",
                'start' => "{$lesson->lesson_date}T{$lesson->lesson_start_time}",
                'end' => "{$lesson->lesson_date}T{$lesson->lesson_end_time}",
                'color' => '#3b82f6', // Blue for scheduled
                'extendedProps' => [
                    'student_id' => $lesson->student_id,
                    'instrument_id' => $lesson->instrument_id,
                    'lesson_duration' => $lesson->lesson_duration,
                ],
            ];
        });

    // Get pending lesson requests
    $requests = LessonRequest::where('teacher_id', $teacherId)
        ->whereBetween('requested_date', [$start, $end])
        ->where('status', LessonRequestStatus::Pending)
        ->with(['student:id,name', 'instrument:id,name'])
        ->get()
        ->map(function ($request) {
            return [
                'id' => "request-{$request->id}",
                'title' => "REQUEST: {$request->student->name} - {$request->instrument->name}",
                'start' => "{$request->requested_date}T{$request->requested_start_time}",
                'end' => "{$request->requested_date}T{$request->requested_end_time}",
                'color' => '#f59e0b', // Amber for pending
                'extendedProps' => [
                    'type' => 'request',
                    'request_id' => $request->id,
                ],
            ];
        });

    return response()->json($lessons->concat($requests));
}
```

#### Student Calendar Events
```php
public function studentEvents(Request $request)
{
    $studentId = auth()->id();
    $start = $request->query('start');
    $end = $request->query('end');

    // Get scheduled lessons
    $lessons = BookedLesson::where('student_id', $studentId)
        ->whereBetween('lesson_date', [$start, $end])
        ->where('status', LessonStatus::Scheduled)
        ->with(['teacher:id,name', 'instrument:id,name'])
        ->get()
        ->map(function ($lesson) {
            return [
                'id' => $lesson->id,
                'title' => "{$lesson->teacher->name} - {$lesson->instrument->name}",
                'start' => "{$lesson->lesson_date}T{$lesson->lesson_start_time}",
                'end' => "{$lesson->lesson_date}T{$lesson->lesson_end_time}",
                'color' => '#10b981', // Green for student's lessons
                'extendedProps' => [
                    'teacher_id' => $lesson->teacher_id,
                    'instrument_id' => $lesson->instrument_id,
                    'lesson_duration' => $lesson->lesson_duration,
                ],
            ];
        });

    // Get assignment due dates
    $assignments = LessonStudentAssignment::where('student_id', $studentId)
        ->whereNotNull('due_date')
        ->whereBetween('due_date', [$start, $end])
        ->with(['lesson:id,title,instrument'])
        ->get()
        ->map(function ($assignment) {
            return [
                'id' => "assignment-{$assignment->id}",
                'title' => "DUE: {$assignment->lesson->title}",
                'start' => $assignment->due_date,
                'allDay' => true,
                'color' => '#6366f1', // Indigo for assignments
                'extendedProps' => [
                    'type' => 'assignment',
                    'assignment_id' => $assignment->id,
                ],
            ];
        });

    return response()->json($lessons->concat($assignments));
}
```

---

## 🔔 Notification System

### Booking Notification Types

#### NewLessonRequestNotification
**Triggers**: When a student submits a lesson request
**Recipients**: The teacher who received the request
**Content**: Request details, student information, preferred time

#### LessonConfirmedNotification
**Triggers**: When a teacher confirms a lesson request
**Recipients**: The student who made the request
**Content**: Confirmed lesson details, date/time information

#### LessonRejectedNotification
**Triggers**: When a teacher rejects a lesson request
**Recipients**: The student who made the request
**Content**: Rejection reason (if provided), next steps

#### LessonRescheduledNotification
**Triggers**: When a teacher suggests an alternative time
**Recipients**: The student who made the request
**Content**: Suggested time, option to accept or decline

#### LessonSuggestionAcceptedNotification
**Triggers**: When a student accepts a teacher's suggestion
**Recipients**: The teacher who made the suggestion
**Content**: Confirmation that lesson is now scheduled

#### BookedLessonCancelledNotification
**Triggers**: When a booked lesson is cancelled
**Recipients**: Both teacher and student
**Content**: Cancellation reason, rescheduling options

#### BookedLessonRescheduledNotification
**Triggers**: When a booked lesson is rescheduled
**Recipients**: Both teacher and student
**Content**: New lesson details, change confirmation

#### LessonLifecycleNotification
**Triggers**: When a lesson status changes (completed, etc.)
**Recipients**: Relevant parties based on status change
**Content**: Status update, next steps if applicable

---

## 🎨 Frontend Integration

### FullCalendar Setup

#### Installation
```json
{
  "dependencies": {
    "@fullcalendar/core": "^6.1.21",
    "@fullcalendar/daygrid": "^6.1.21",
    "@fullcalendar/timegrid": "^6.1.21",
    "@fullcalendar/interaction": "^6.1.21"
  }
}
```

#### Calendar Initialization (Teacher)
```javascript
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('teacher-calendar');
    
    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '/teacher/calendar/events',
        eventClick: function(info) {
            if (info.event.extendedProps.type === 'request') {
                // Open lesson request modal
                window.location.href = `/teacher/lesson-requests/${info.event.extendedProps.request_id}`;
            } else {
                // Open booked lesson details
                window.location.href = `/teacher/booking-management/${info.event.id}`;
            }
        },
        selectable: true,
        select: function(info) {
            // Open new lesson request creation modal
            // with pre-filled date/time
        }
    });
    
    calendar.render();
});
```

#### Calendar Initialization (Student)
```javascript
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('student-calendar');
    
    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '/student/calendar/events',
        eventClick: function(info) {
            if (info.event.extendedProps.type === 'assignment') {
                // Open assignment details
                window.location.href = `/lessons/${info.event.extendedProps.lesson_id}`;
            } else {
                // Open booked lesson details
                window.location.href = `/student/booking-management/${info.event.id}`;
            }
        }
    });
    
    calendar.render();
});
```

---

## 🛠️ Validation & Rules

### Lesson Request Validation
```php
class StoreLessonRequestSubmissionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'teacher_id' => 'required|exists:users,id',
            'instrument_id' => 'required|exists:instruments,id',
            'requested_date' => 'required|date|after:today',
            'requested_start_time' => 'required|date_format:H:i',
            'requested_end_time' => 'required|date_format:H:i|after:requested_start_time',
            'lesson_duration' => 'required|integer|in:30,45,60,90',
            'student_note' => 'nullable|string|max:500',
        ];
    }
}
```

### Teacher Availability Validation
```php
protected function validateTeacherAvailability($teacherId, $date, $startTime, $endTime)
{
    $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));
    
    $availability = TeacherAvailability::where('teacher_id', $teacherId)
        ->where('day_of_week', $dayOfWeek)
        ->where('is_active', true)
        ->where('start_time', '<=', $startTime)
        ->where('end_time', '>=', $endTime)
        ->first();
    
    if (!$availability) {
        throw ValidationException::withMessages([
            'requested_date' => 'Teacher is not available at the requested time.'
        ]);
    }
    
    // Check for existing bookings
    $existingBooking = BookedLesson::where('teacher_id', $teacherId)
        ->where('lesson_date', $date)
        ->where('status', LessonStatus::Scheduled)
        ->where(function ($query) use ($startTime, $endTime) {
            $query->whereBetween('lesson_start_time', [$startTime, $endTime])
                  ->orWhereBetween('lesson_end_time', [$startTime, $endTime])
                  ->orWhere(function ($q) use ($startTime, $endTime) {
                      $q->where('lesson_start_time', '<', $startTime)
                        ->where('lesson_end_time', '>', $endTime);
                  });
        })
        ->exists();
    
    if ($existingBooking) {
        throw ValidationException::withMessages([
            'requested_date' => 'Teacher already has a lesson scheduled at this time.'
        ]);
    }
}
```

---

## 📈 Dashboard Integration

### Teacher Dashboard Booking Stats
```php
// In Teacher Dashboard Livewire component
$lessonManagementStats = [
    'today' => BookedLesson::where('teacher_id', $teacherId)
        ->whereDate('lesson_date', $today)
        ->where('status', LessonStatus::Scheduled)
        ->count(),
    'upcoming' => BookedLesson::where('teacher_id', $teacherId)
        ->whereDate('lesson_date', '>', $today)
        ->where('status', LessonStatus::Scheduled)
        ->count(),
    'completed' => BookedLesson::where('teacher_id', $teacherId)
        ->where('status', LessonStatus::Completed)
        ->count(),
    'cancelled' => BookedLesson::where('teacher_id', $teacherId)
        ->where('status', LessonStatus::Cancelled)
        ->count(),
];

$pendingLessonRequests = LessonRequest::where('teacher_id', $teacherId)
    ->where('status', LessonRequestStatus::Pending)
    ->with(['student:id,name', 'instrument:id,name'])
    ->orderBy('requested_date')
    ->orderBy('requested_start_time')
    ->limit(5)
    ->get();
```

### Student Dashboard Booking Stats
```php
// In Student Dashboard Livewire component
$upcomingBookings = BookedLesson::where('student_id', $studentId)
    ->where('status', LessonStatus::Scheduled)
    ->whereDate('lesson_date', '>=', now())
    ->with(['teacher:id,name', 'instrument:id,name'])
    ->orderBy('lesson_date')
    ->orderBy('lesson_start_time')
    ->limit(5)
    ->get();
```

---

## 🔍 Common Use Cases

### Use Case 1: Student Requests Lesson
1. Student navigates to lesson request form
2. Selects teacher, instrument, preferred date/time
3. Adds optional note for teacher
4. Submits request
5. System validates teacher availability
6. Creates LessonRequest with status 'pending'
7. Teacher receives notification
8. Request appears in teacher's dashboard

### Use Case 2: Teacher Confirms Request
1. Teacher sees pending request in dashboard
2. Reviews request details and student information
3. Clicks "Confirm" button
4. System creates BookedLesson record
5. Updates LessonRequest status to 'teacher_confirmed'
6. Student receives confirmation notification
7. Lesson appears in both calendars

### Use Case 3: Teacher Suggests Alternative Time
1. Teacher sees pending request but unavailable at requested time
2. Clicks "Suggest Alternative Time"
3. Selects available date/time from calendar
4. Adds optional note explaining the change
5. Submits suggestion
6. System updates LessonRequest with suggested time
7. Updates status to 'teacher_rescheduled'
8. Student receives notification with suggestion
9. Student can accept or decline the suggestion

### Use Case 4: Lesson Rescheduling
1. Teacher or student needs to change scheduled lesson time
2. Opens lesson details
3. Clicks "Reschedule" button
4. Selects new available date/time
5. Adds reason for rescheduling
6. Submits change
7. System updates BookedLesson with new time
8. Sets rescheduled_at timestamp
9. Both parties receive notification
10. Calendar events update automatically

### Use Case 5: Lesson Cancellation
1. Teacher or student needs to cancel lesson
2. Opens lesson details
3. Clicks "Cancel" button
4. Provides cancellation reason
5. Confirms cancellation
6. System updates status to 'cancelled'
7. Sets cancelled_at timestamp
8. Stores cancellation reason
9. Both parties receive notification
10. Calendar event removed or marked as cancelled

---

## 🚀 Performance Optimizations

### Database Indexing
```sql
-- Optimized indexes for booking queries
CREATE INDEX idx_lesson_requests_teacher_status ON lesson_requests(teacher_id, status);
CREATE INDEX idx_lesson_requests_student_status ON lesson_requests(student_id, status);
CREATE INDEX idx_booked_lessons_teacher_date ON booked_lessons(teacher_id, lesson_date);
CREATE INDEX idx_booked_lessons_student_date ON booked_lessons(student_id, lesson_date);
CREATE INDEX idx_teacher_availability_teacher_day ON teacher_availability(teacher_id, day_of_week);
```

### Query Optimization
```php
// Eager loading to prevent N+1 queries
$requests = LessonRequest::with(['student:id,name', 'instrument:id,name'])
    ->where('teacher_id', $teacherId)
    ->get();

// Efficient date range queries
$lessons = BookedLesson::where('teacher_id', $teacherId)
    ->whereBetween('lesson_date', [$startDate, $endDate])
    ->where('status', LessonStatus::Scheduled)
    ->get();
```

### Caching Strategy
```php
// Cache teacher availability for performance
$availability = Cache::remember(
    "teacher_availability_{$teacherId}",
    now()->addHours(6),
    function () use ($teacherId) {
        return TeacherAvailability::where('teacher_id', $teacherId)
            ->where('is_active', true)
            ->get();
    }
);
```

---

## 🧪 Testing Recommendations

### Unit Tests
- Test LessonRequest status transitions
- Test BookedLesson lifecycle methods
- Test availability validation logic
- Test notification dispatching

### Feature Tests
- Test complete booking workflow
- Test teacher confirmation flow
- Test student acceptance flow
- Test rescheduling process
- Test cancellation process
- Test calendar API responses

### Browser Tests
- Test calendar interactivity
- Test modal functionality
- Test real-time updates
- Test notification display

---

## 📚 Additional Resources

### Related Documentation
- [Static Lesson System Guide](FEATURES_GUIDE.md)
- [Notification System Documentation](NOTIFICATION_GUIDE.md)
- [Calendar Integration Guide](CALENDAR_SETUP.md)
- [API Documentation](API_DOCUMENTATION.md)

### Key Files Reference
- Models: `Modules/Booking/Models/`
- Controllers: `app/Http/Controllers/` (Student/Teacher folders)
- Enums: `Modules/Booking/Enums/`
- Migrations: `Modules/Booking/Database/migrations/`
- Calendar: `app/Http/Controllers/Calendar/`

---

**Last Updated**: August 19, 2026  
**Version**: 1.0  
**Status**: Production Ready