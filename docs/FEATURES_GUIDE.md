# Complete Feature Implementation Guide

This document provides comprehensive documentation for all features implemented in the SOHMC Music School system, including both the static lesson management system and the dynamic booking/scheduling system.

## Overview

The SOHMC Music School system implements two complementary lesson management approaches:

1. **Static Lesson Management** - Teachers create lesson content, assign to students, track progress
2. **Dynamic Booking System** - Students request lessons, teachers manage availability, real-time scheduling

Additionally, the system includes comprehensive user management, family accounts, notifications, and role-based dashboards.

---

## Part 1: Static Lesson Management System

### 1.1 Assignment Tracking System

**Purpose**: Track which lessons are assigned to which students, with status and due dates.

#### Database Schema

##### `lesson_student_assignments` table
```sql
CREATE TABLE lesson_student_assignments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    lesson_id BIGINT NOT NULL,
    student_id BIGINT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    due_date DATE NULL,
    status VARCHAR(255) DEFAULT 'assigned',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY (lesson_id, student_id),
    INDEX (student_id),
    INDEX (status)
);
```

#### Models & Relationships

##### LessonStudentAssignment Model
```php
class LessonStudentAssignment extends Model
{
    protected $table = 'lesson_student_assignments';
    
    protected $fillable = [
        'lesson_id', 'student_id', 'assigned_at', 'due_date', 'status'
    ];
    
    protected function casts(): array
    {
        return [
            'status' => AssignmentStatus::class,
            'assigned_at' => 'datetime',
            'due_date' => 'date',
        ];
    }
    
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }
    
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    public function comments(): HasMany
    {
        return $this->hasMany(LessonAssignmentComment::class, 'lesson_student_assignment_id');
    }
    
    public function scopeCompletedByStudent($query, User $student): void
    {
        $query->where('student_id', $student->id)
            ->where('status', AssignmentStatus::Completed->value);
    }
    
    public function scopeAssignedToStudent($query, User $student): void
    {
        $query->where('student_id', $student->id)
            ->whereIn('status', [
                AssignmentStatus::Assigned->value,
                AssignmentStatus::Started->value,
                AssignmentStatus::InProgress->value,
            ]);
    }
}
```

#### Assignment Status Enum

```php
enum AssignmentStatus: string
{
    case Assigned = 'assigned';
    case Started = 'started';
    case InProgress = 'in_progress';
    case Completed = 'completed';

    public function label(): string
    {
        return match($this) {
            self::Assigned => 'Assigned',
            self::Started => 'Started',
            self::InProgress => 'In Progress',
            self::Completed => 'Completed',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Assigned => 'gray',
            self::Started => 'blue',
            self::InProgress => 'yellow',
            self::Completed => 'green',
        };
    }
}
```

#### Teacher Workflow

1. Navigate to `/admin/assignments` (Assignment Dashboard)
2. Click "+ Assign Lesson" button
3. Select published lesson from dropdown
4. Select one or more students from multi-select
5. Optionally set due date
6. Submit to create assignments
7. View table of all assignments with status badges
8. Click status dropdown to update individual assignment status

#### Student Workflow

1. Navigate to `/lessons` (Student Dashboard)
2. View assigned lessons with status badges
3. Click action buttons to update progress:
   - "Start" → mark as `started`
   - "Next" → increment status
   - "Done" → mark as `completed`
4. Updates are saved immediately via Livewire
5. Card status badge refreshes automatically

### 1.2 Assignment Comments System

**Purpose**: Allow teachers to add notes and feedback to student assignments.

#### Database Schema

##### `lesson_assignment_comments` table
```sql
CREATE TABLE lesson_assignment_comments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    lesson_student_assignment_id BIGINT NOT NULL,
    teacher_id BIGINT NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (lesson_student_assignment_id) REFERENCES lesson_student_assignments(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### Model

```php
class LessonAssignmentComment extends Model
{
    protected $table = 'lesson_assignment_comments';
    
    protected $fillable = ['lesson_student_assignment_id', 'teacher_id', 'body'];
    
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(LessonStudentAssignment::class, 'lesson_student_assignment_id');
    }
    
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
```

#### Teacher UI Integration

In `AssignmentDashboard.php` Livewire component:

```php
public function startComment(int $assignmentId): void
{
    $assignment = LessonStudentAssignment::query()
        ->with(['lesson:id,title,teacher_id', 'student:id,name'])
        ->whereHas('lesson', function ($query) {
            $query->where('teacher_id', auth()->id());
        })
        ->findOrFail($assignmentId);

    $this->commentAssignmentId = $assignment->id;
    $this->commentBody = (string) optional($assignment->latestComment)->body;
}

public function saveComment(): void
{
    $this->validate([
        'commentAssignmentId' => 'required|integer|exists:lesson_student_assignments,id',
        'commentBody' => 'required|string|min:5|max:5000',
    ]);

    $assignment = LessonStudentAssignment::query()
        ->whereHas('lesson', function ($query) {
            $query->where('teacher_id', auth()->id());
        })
        ->findOrFail($this->commentAssignmentId);

    LessonAssignmentComment::create([
        'lesson_student_assignment_id' => $assignment->id,
        'teacher_id' => auth()->id(),
        'body' => $this->commentBody,
    ]);

    $this->dispatch('notify', message: 'Teacher note saved.', type: 'success');
    $this->cancelComment();
}
```

### 1.3 Global Notes System

**Purpose**: Teacher notes visible to all students assigned to a lesson.

#### Database Schema

```sql
ALTER TABLE lessons ADD COLUMN global_note TEXT NULL;
```

#### Usage in Lesson Model

```php
class Lesson extends BaseModel
{
    protected $fillable = [
        // ... other fields
        'global_note',
    ];
}
```

#### Frontend Display

In lesson detail views, global notes are displayed prominently:

```blade
@if($lesson->global_note)
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
        <h4 class="font-bold text-blue-700">Teacher's Note</h4>
        <p class="text-blue-600">{{ $lesson->global_note }}</p>
    </div>
@endif
```

### 1.4 Instrument Management

#### Basic Instrument System

**Purpose**: Organize lessons by instrument type.

##### Database Schema

```sql
ALTER TABLE lessons ADD COLUMN instrument VARCHAR(255) NULL;
```

##### Supported Instruments
- `piano`
- `guitar`
- `vocals` or `voice / singing`
- `percussion`
- `steelpan`
- `music theory`
- `null` (general/theory lessons)

##### Frontend Implementation

```php
// In LessonSearch Livewire component
#[\Livewire\Attributes\Computed]
public function instruments(): Collection
{
    return Lesson::query()
        ->whereNotNull('instrument')
        ->distinct()
        ->pluck('instrument')
        ->sort();
}
```

#### Advanced Instrument System

**Purpose**: Normalized instrument management with teacher relationships.

##### Database Schema

```sql
CREATE TABLE instruments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

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

##### Model

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

##### Seeder

```php
class BookingInstrumentSeeder extends Seeder
{
    public function run(): void
    {
        $defaultInstruments = [
            ['name' => 'Piano', 'description' => 'Keyboard technique, sight reading, and repertoire.'],
            ['name' => 'Guitar', 'description' => 'Acoustic and electric guitar fundamentals.'],
            ['name' => 'Saxophone', 'description' => 'Tone production, breath control, and melodic phrasing.'],
            ['name' => 'Voice / Singing', 'description' => 'Voice training, breathing, and performance.'],
            ['name' => 'Violin', 'description' => 'String fundamentals and musical expression.'],
            ['name' => 'Keyboard', 'description' => 'Modern keyboard skills, chords, voicing, and accompaniment.'],
            ['name' => 'Steelpan', 'description' => 'Steelpan technique, tone control, and repertoire.'],
            ['name' => 'Music Theory', 'description' => 'Foundational theory, notation, harmony, and ear training.'],
        ];

        foreach ($defaultInstruments as $instrumentData) {
            Instrument::query()->updateOrCreate(
                ['name' => $instrumentData['name']],
                [
                    'description' => $instrumentData['description'],
                    'is_active' => true,
                ]
            );
        }

        // Auto-assign instruments to teachers
        $instrumentIds = Instrument::query()->active()->pluck('id');
        $teachers = User::role('teacher')->get();

        foreach ($teachers as $teacher) {
            if ($teacher->teachingInstruments()->count() === 0) {
                $teacher->teachingInstruments()->syncWithoutDetaching($instrumentIds);
            }
        }
    }
}
```

### 1.5 Search & Filtering

**Purpose**: Allow students/teachers to find lessons quickly with reactive UI.

#### Implementation: LessonSearch Livewire Component

```php
class LessonSearch extends Component
{
    use WithPagination;

    public string $search = '';
    public string $instrumentFilter = '';
    public string $statusFilter = '';
    public string $tab = 'all'; // all, assigned, completed

    #[\Livewire\Attributes\Computed]
    public function lessons()
    {
        $user = auth()->user();
        $query = Lesson::query()->with('teacher', 'assignedStudents');

        // Role-based filtering
        if ($user->hasRole('student')) {
            $query->whereHas('assignedStudents', function ($q) use ($user) {
                $q->where('student_id', $user->id);
            });
        } elseif ($user->hasRole('teacher')) {
            $query->where('teacher_id', $user->id);
        }

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        // Instrument filter
        if ($this->instrumentFilter) {
            $query->where('instrument', $this->instrumentFilter);
        }

        // Status filter (only when not on 'all' tab)
        if ($this->tab !== 'all' && $this->statusFilter) {
            $query->whereHas('assignedStudents', function ($q) use ($user) {
                $q->where('student_id', $user->id)
                  ->where('status', $this->statusFilter);
            });
        }

        return $query->orderBy('order')->paginate(12);
    }

    #[\Livewire\Attributes\Computed]
    public function instruments(): Collection
    {
        return Lesson::query()
            ->whereNotNull('instrument')
            ->distinct()
            ->pluck('instrument')
            ->sort();
    }
}
```

#### UI Features

- **Full-text search**: Search by title or description
- **Instrument filter**: Dropdown with all available instruments
- **Status filter**: Filter by assignment status (assigned, started, in_progress, completed)
- **Tab navigation**: All Lessons | Assigned | Completed
- **Role-based filtering**: Students see only their assignments, teachers see only their lessons
- **Pagination**: 12 lessons per page
- **Reactive updates**: All changes via `wire:model.live` trigger Livewire updates without page reload

### 1.6 Progress Tracking

**Purpose**: Enable students to update their progress through lessons and allow teachers to monitor.

#### Student Progress Workflow

1. Student views assigned lesson on `/lessons` page
2. Cards show current status badge (color-coded)
3. If not completed, student sees action buttons:
   - "Start" → mark as `started`
   - "Next" → increment status
   - "Done" → mark as `completed`
4. Updates are saved immediately via Livewire
5. Card status badge refreshes

#### Teacher Progress Dashboard

Navigate to `/admin/assignments` or `/teacher/assignments`:

- **Summary cards**:
  - Total Assigned (gray)
  - Total Started (blue)
  - Total In Progress (yellow)
  - Total Completed (green)

- **Data table** shows:
  - Student name
  - Lesson title
  - Status dropdown (click to change)
  - Due date (if set)
  - Assignment date
  - Latest teacher comment

#### Status Colors (Tailwind)

- `assigned` → `bg-gray-100 text-gray-800`
- `started` → `bg-blue-100 text-blue-800`
- `in_progress` → `bg-yellow-100 text-yellow-800`
- `completed` → `bg-green-100 text-green-800`

---

## Part 2: Dynamic Booking System

### 2.1 Lesson Request Workflow

**Purpose**: Allow students to request lessons with preferred times and instruments.

#### Database Schema

##### `lesson_requests` table
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

#### LessonRequestStatus Enum

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

#### Booking Workflow States

1. **Pending** → Initial state when student submits request
2. **TeacherConfirmed** → Teacher confirms the requested time
3. **TeacherRescheduled** → Teacher suggests alternative time
4. **StudentAccepted** → Student accepts (confirmed or suggested) time
5. **StudentDeclined** → Student declines the suggested time
6. **Cancelled** → Request cancelled by either party

#### Student Request Creation

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

    // Validate teacher availability
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

    // Notify teacher
    $lessonRequest->teacher->notify(new NewLessonRequestNotification($lessonRequest));

    return redirect()->back()->with('success', 'Lesson request submitted successfully.');
}
```

#### Teacher Response Actions

##### Confirm Request
```php
public function confirm(LessonRequest $lessonRequest)
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

    $lessonRequest->update(['status' => LessonRequestStatus::TeacherConfirmed]);
    $lessonRequest->student->notify(new LessonConfirmedNotification($lessonRequest, $bookedLesson));

    return redirect()->back()->with('success', 'Lesson confirmed and scheduled.');
}
```

##### Suggest Alternative Time
```php
public function reschedule(LessonRequest $lessonRequest, Request $request)
{
    abort_unless($lessonRequest->teacher_id === auth()->id(), 403);
    abort_unless($lessonRequest->status === LessonRequestStatus::Pending, 403);

    $validated = $request->validate([
        'suggested_date' => 'required|date|after:today',
        'suggested_start_time' => 'required|date_format:H:i',
        'suggested_end_time' => 'required|date_format:H:i|after:suggested_start_time',
        'teacher_note' => 'nullable|string|max:500',
    ]);

    $lessonRequest->update([
        'suggested_date' => $validated['suggested_date'],
        'suggested_start_time' => $validated['suggested_start_time'],
        'suggested_end_time' => $validated['suggested_end_time'],
        'teacher_note' => $validated['teacher_note'] ?? null,
        'status' => LessonRequestStatus::TeacherRescheduled,
    ]);

    $lessonRequest->student->notify(new LessonRescheduledNotification($lessonRequest));

    return redirect()->back()->with('success', 'Alternative time suggested to student.');
}
```

##### Reject Request
```php
public function reject(LessonRequest $lessonRequest, Request $request)
{
    abort_unless($lessonRequest->teacher_id === auth()->id(), 403);
    abort_unless($lessonRequest->status === LessonRequestStatus::Pending, 403);

    $validated = $request->validate([
        'teacher_note' => 'nullable|string|max:500',
    ]);

    $lessonRequest->update([
        'teacher_note' => $validated['teacher_note'] ?? null,
        'status' => LessonRequestStatus::Cancelled,
    ]);

    $lessonRequest->student->notify(new LessonRejectedNotification($lessonRequest));

    return redirect()->back()->with('success', 'Lesson request rejected.');
}
```

#### Student Response Actions

##### Accept Teacher Suggestion
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

    $lessonRequest->update(['status' => LessonRequestStatus::StudentAccepted]);
    $lessonRequest->teacher->notify(new LessonSuggestionAcceptedNotification($lessonRequest));

    return redirect()->back()->with('success', 'Lesson time accepted. Your lesson is scheduled!');
}
```

### 2.2 Booked Lessons Management

**Purpose**: Manage scheduled lessons with full lifecycle tracking.

#### Database Schema

##### `booked_lessons` table
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

#### LessonStatus Enum (Booking)

```php
enum LessonStatus: string
{
    case Scheduled = 'scheduled';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
```

#### BookedLesson Model

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

#### Lesson Lifecycle Management

##### Complete Lesson
```php
public function complete(BookedLesson $bookedLesson, Request $request)
{
    abort_unless($bookedLesson->teacher_id === auth()->id(), 403);
    abort_unless($bookedLesson->status === LessonStatus::Scheduled, 403);

    $bookedLesson->update([
        'status' => LessonStatus::Completed,
        'completed_at' => now(),
    ]);

    $bookedLesson->student->notify(new LessonLifecycleNotification($bookedLesson, 'completed'));

    return redirect()->back()->with('success', 'Lesson marked as completed.');
}
```

##### Cancel Lesson
```php
public function cancel(BookedLesson $bookedLesson, Request $request)
{
    abort_unless($bookedLesson->teacher_id === auth()->id(), 403);
    abort_unless($bookedLesson->status === LessonStatus::Scheduled, 403);

    $validated = $request->validate([
        'cancellation_reason' => 'nullable|string|max:500',
    ]);

    $bookedLesson->update([
        'status' => LessonStatus::Cancelled,
        'cancelled_at' => now(),
        'cancellation_reason' => $validated['cancellation_reason'] ?? null,
    ]);

    $bookedLesson->student->notify(new BookedLessonCancelledNotification($bookedLesson));

    return redirect()->back()->with('success', 'Lesson cancelled successfully.');
}
```

##### Reschedule Lesson
```php
public function reschedule(BookedLesson $bookedLesson, Request $request)
{
    abort_unless($bookedLesson->teacher_id === auth()->id(), 403);
    abort_unless($bookedLesson->status === LessonStatus::Scheduled, 403);

    $validated = $request->validate([
        'new_date' => 'required|date|after:today',
        'new_start_time' => 'required|date_format:H:i',
        'new_end_time' => 'required|date_format:H:i|after:new_start_time',
        'reschedule_reason' => 'nullable|string|max:500',
    ]);

    $bookedLesson->update([
        'lesson_date' => $validated['new_date'],
        'lesson_start_time' => $validated['new_start_time'],
        'lesson_end_time' => $validated['new_end_time'],
        'rescheduled_at' => now(),
    ]);

    $bookedLesson->student->notify(new BookedLessonRescheduledNotification($bookedLesson));

    return redirect()->back()->with('success', 'Lesson rescheduled successfully.');
}
```

### 2.3 Teacher Availability Management

**Purpose**: Define when teachers are available for lessons.

#### Database Schema

##### `teacher_availability` table
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

#### TeacherAvailability Model

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

#### Availability Validation

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

### 2.4 Calendar Integration

**Purpose**: Visual scheduling interface for both teachers and students.

#### FullCalendar Setup

##### Installation
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

##### Calendar Controller

```php
class LessonCalendarController extends Controller
{
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
                    'color' => '#3b82f6',
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
                    'color' => '#f59e0b',
                    'extendedProps' => [
                        'type' => 'request',
                        'request_id' => $request->id,
                    ],
                ];
            });

        return response()->json($lessons->concat($requests));
    }

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
                    'color' => '#10b981',
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
                    'color' => '#6366f1',
                    'extendedProps' => [
                        'type' => 'assignment',
                        'assignment_id' => $assignment->id,
                    ],
                ];
            });

        return response()->json($lessons->concat($assignments));
    }
}
```

---

## Part 3: User Management & Family System

### 3.1 Role-Based Access Control

#### User Roles

1. **Super Admin**: Full system access, user management, system configuration
2. **Administrator**: Backend management, content management, user oversight
3. **Teacher**: Lesson creation, assignment management, booking management
4. **Student**: View lessons, update progress, request bookings
5. **Parent**: Monitor children's lessons and progress

#### Permission System

Using Spatie Laravel Permission for fine-grained access control:

```php
// Example permission checks
$user->hasPermissionTo('edit_lessons');
$user->hasPermissionTo('manage_bookings');
$user->hasPermissionTo('view_backend');
```

#### Dynamic Dashboard Routing

```php
// In User model
public function dashboardRouteName(): string
{
    if ($this->hasRole('super admin') || $this->hasRole('administrator')) {
        return 'backend.dashboard';
    }

    if ($this->hasRole('teacher')) {
        return 'teacher.dashboard';
    }

    if ($this->hasRole('student')) {
        return 'student.dashboard';
    }

    if ($this->hasRole('parent')) {
        return 'parent.dashboard';
    }

    return 'frontend.index';
}
```

### 3.2 Family Account System

**Purpose**: Allow parents to monitor their children's lessons and progress.

#### Database Schema

##### `parent_student` table
```sql
CREATE TABLE parent_student (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    parent_id BIGINT NOT NULL,
    student_id BIGINT NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY (parent_id, student_id)
);
```

#### User Model Relationships

```php
// In User model
public function children(): BelongsToMany
{
    return $this->belongsToMany(self::class, 'parent_student', 'parent_id', 'student_id')
        ->withTimestamps();
}

public function parents(): BelongsToMany
{
    return $this->belongsToMany(self::class, 'parent_student', 'student_id', 'parent_id')
        ->withTimestamps();
}
```

#### Parent Access Implementation

```php
// In LessonController
public function index(Request $request)
{
    $user = Auth::user();

    if ($user->hasRole('student')) {
        $lessonsQuery = Lesson::whereHas('students', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        });
    } elseif ($user->hasRole('parent')) {
        $lessonsQuery = Lesson::whereHas('students.parents', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        });
    } elseif ($user->hasRole('teacher')) {
        $lessonsQuery = Lesson::where('teacher_id', $user->id);
    } else {
        $lessonsQuery = Lesson::query();
    }

    $lessons = $lessonsQuery->orderBy('order')->get();
    // ... rest of the method
}
```

#### Parent Dashboard

```php
class ParentDashboard extends Component
{
    public function render()
    {
        $parentId = auth()->id();
        $childrenIds = auth()->user()->children()->pluck('id');

        $childrenAssignments = LessonStudentAssignment::whereIn('student_id', $childrenIds)
            ->with(['lesson:id,title,instrument', 'student:id,name'])
            ->orderBy('assigned_at', 'desc')
            ->get();

        $upcomingLessons = BookedLesson::whereIn('student_id', $childrenIds)
            ->where('status', LessonStatus::Scheduled)
            ->whereDate('lesson_date', '>=', now())
            ->with(['teacher:id,name', 'instrument:id,name'])
            ->orderBy('lesson_date')
            ->limit(10)
            ->get();

        return view('parent.dashboard', [
            'childrenAssignments' => $childrenAssignments,
            'upcomingLessons' => $upcomingLessons,
        ])->layout('layouts.app');
    }
}
```

---

## Part 4: Notification System

### 4.1 Assignment Notifications

#### AssignmentStatusUpdatedNotification
**Triggers**: When teachers update assignment status
**Recipients**: Students affected by status changes

#### LessonAssignedNotification
**Triggers**: When teachers assign new lessons to students
**Recipients**: Students receiving new assignments

### 4.2 Booking Notifications

#### NewLessonRequestNotification
**Triggers**: When students submit lesson requests
**Recipients**: Teachers receiving requests

#### LessonConfirmedNotification
**Triggers**: When teachers confirm lesson requests
**Recipients**: Students whose requests were confirmed

#### LessonRejectedNotification
**Triggers**: When teachers reject lesson requests
**Recipients**: Students whose requests were rejected

#### LessonRescheduledNotification
**Triggers**: When teachers suggest alternative times
**Recipients**: Students whose requests need time changes

#### LessonSuggestionAcceptedNotification
**Triggers**: When students accept teacher's time suggestions
**Recipients**: Teachers who made suggestions

#### BookedLessonCancelledNotification
**Triggers**: When booked lessons are cancelled
**Recipients**: Both teachers and students

#### BookedLessonRescheduledNotification
**Triggers**: When booked lessons are rescheduled
**Recipients**: Both teachers and students

#### LessonLifecycleNotification
**Triggers**: When lesson status changes (completed, etc.)
**Recipients**: Relevant parties based on status change

### 4.3 System Notifications

#### UserAccountCreated
**Triggers**: When new user accounts are created
**Recipients**: New users (email verification)

#### NewRegistrationNotification
**Triggers**: New user registrations
**Recipients**: Administrators

### 4.4 Notification UI

#### Notification Bell Component

```php
class NotificationBell extends Component
{
    public function render()
    {
        $unreadCount = auth()->user()->unreadNotifications()->count();
        $recentNotifications = auth()->user()->unreadNotifications()
            ->latest()
            ->take(5)
            ->get();

        return view('components.notifications.notification-bell', [
            'unreadCount' => $unreadCount,
            'recentNotifications' => $recentNotifications,
        ]);
    }
}
```

#### Notification Management

```php
class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        abort_unless($notification->notifiable_id === auth()->id(), 403);
        $notification->markAsRead();
        return back();
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        return back();
    }

    public function destroy(Notification $notification)
    {
        abort_unless($notification->notifiable_id === auth()->id(), 403);
        $notification->delete();
        return back();
    }
}
```

---

## Part 5: UI/UX Components

### 5.1 Student Dashboard (`/lessons`)

#### Features
- Hero section with title and description
- Search bar with placeholder
- Filter controls (responsive grid)
- Tab navigation (All / Assigned / Completed)
- Card grid (1 col mobile, 2 cols tablet, 3 cols desktop)

#### Card Components
Each lesson card includes:
- Gradient header with lesson title and instrument
- Teacher name
- Description (truncated to 3 lines)
- Status badge (if assigned)
- Due date (if set)
- "View Lesson" CTA button
- Progress buttons (for students)

#### Responsive Design
- Mobile: Single column cards
- Tablet: Two column cards
- Desktop: Three column cards
- Touch-friendly buttons and inputs

### 5.2 Teacher Dashboard (`/teacher/dashboard`)

#### Statistics Cards
- Lessons total / published
- Assignments total / due soon
- Booking stats (today, upcoming, completed, cancelled)
- Progress tracking (assigned, started, in_progress, completed)

#### Management Sections
- Pending lesson requests
- Upcoming assignments
- Today's scheduled lessons
- Upcoming booked lessons
- Recent completed lessons
- Notifications panel

### 5.3 Assignment Dashboard (`/admin/assignments` or `/teacher/assignments`)

#### Features
- Header with "+ Assign Lesson" button
- Four summary cards (color-coded by status)
- Data table with:
  - Lesson name
  - Student name
  - Status dropdown (click to change)
  - Due date display
  - Assigned date
  - Latest teacher comment
  - Comment management button

#### Teacher Comments Integration
- "Add Comment" button for each assignment
- Modal form for comment input
- Latest comment display in table
- Real-time comment updates

### 5.4 Booking Management Interface

#### Teacher Booking Management
- List of all booked lessons
- Action buttons: Complete, Cancel, Reschedule
- Lesson details with student information
- Status indicators and timestamps
- Cancellation reasons when applicable

#### Student Booking Management
- Upcoming scheduled lessons
- Lesson details with teacher information
- Request history
- Status tracking

### 5.5 Calendar Interface

#### Teacher Calendar
- FullCalendar integration with time grid
- Scheduled lessons (blue events)
- Pending requests (amber events)
- Click events to view details/manage
- Week/month/day views
- Responsive design

#### Student Calendar
- Scheduled lessons (green events)
- Assignment due dates (indigo events)
- Click events to view details
- Week/month/day views
- Responsive design

---

## Part 6: Code Standards & Best Practices

### 6.1 Architecture Patterns

#### Modular Design
- Domain separation in Modules/
- Each module has its own models, migrations, relationships
- Shared functionality in app/

#### Repository Pattern
- Model scopes for business logic
- Complex queries encapsulated in scopes
- Reusable query builders

#### Service Layer
- Controllers handle HTTP concerns
- Models handle business logic
- Services handle complex operations

#### Event-Driven
- Laravel events for system actions
- Notification system via events
- Decoupled components

### 6.2 Code Quality

#### PHP Standards
- PSR-12 coding standards
- Type hints on all methods
- Return type declarations
- Parameter typing
- Property typing

#### Laravel Best Practices
- Form Request validation
- Resource controllers
- Route model binding
- Middleware for authorization
- Service providers for organization

#### Database Design
- Proper foreign key constraints
- Strategic indexing
- Cascade delete where appropriate
- Timestamps for audit trails
- Soft deletes for data recovery

### 6.3 Security

#### Authentication
- Laravel's built-in authentication
- Breeze for starter kits
- Socialite for social login
- Password hashing

#### Authorization
- Spatie permissions
- Middleware protection
- Role-based access control
- Policy classes for authorization

#### Input Validation
- Server-side validation
- Form Request classes
- Custom validation rules
- Sanitization of user input

#### CSRF Protection
- Built-in CSRF tokens
- Form auto-inclusion
- API token authentication

### 6.4 Performance

#### Database Optimization
- Eager loading to prevent N+1
- Query optimization
- Strategic indexing
- Pagination for large datasets
- Query caching

#### Frontend Performance
- Vite for asset bundling
- Lazy loading components
- Image optimization
- CSS/JS minification
- HTTP caching headers

#### Caching Strategy
- Permission caching
- Query result caching
- Route caching
- View caching
- Config caching

---

## Part 7: Testing & Development

### 7.1 Development Workflow

#### Setup
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
```

#### Development Server
```bash
# Terminal 1: PHP server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

#### Database Management
```bash
# Fresh start
php artisan migrate:fresh --seed

# Individual seeders
php artisan db:seed --class=LessonSeeder
php artisan db:seed --class=BookingInstrumentSeeder
```

### 7.2 Testing Recommendations

#### Unit Tests
- Model relationships
- Enum functionality
- Scopes and query builders
- Validation rules
- Business logic methods

#### Feature Tests
- Complete booking workflow
- Assignment status transitions
- Role-based access control
- Notification dispatching
- Calendar API responses

#### Browser Tests
- Calendar interactivity
- Modal functionality
- Real-time updates
- Form submissions
- Navigation flows

### 7.3 Debugging Tools

#### Laravel Debugbar
- Query analysis
- Request profiling
- View rendering
- Route information

#### Laravel Pail
- Real-time log monitoring
- Filtered log viewing
- Performance tracking

#### Telescope (if installed)
- Request monitoring
- Job tracking
- Notification viewing
- Exception tracking

---

## Part 8: Deployment

### 8.1 Production Build

```bash
# Optimize for production
composer install --optimize-autoloader --no-dev
npm install
npm run build
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 8.2 Environment Configuration

Ensure `.env` is configured for production:
- `APP_ENV=production`
- `APP_DEBUG=false`
- Database credentials
- `APP_URL` set to domain
- Email configuration
- File system settings

### 8.3 Server Requirements

- PHP 8.2+
- MySQL 5.7+ / PostgreSQL 9.6+
- Composer
- Node.js & NPM
- Web server (Apache/Nginx)
- SSL certificate

### 8.4 Monitoring & Maintenance

#### Log Management
- Regular log rotation
- Error log monitoring
- Performance tracking
- Storage cleanup

#### Backup Strategy
- Database backups (Spatie Backup)
- File system backups
- Configuration backups
- Disaster recovery plan

#### Updates
- Regular dependency updates
- Security patching
- Laravel version updates
- Feature deployment process

---

## Conclusion

The SOHMC Music School system represents a comprehensive music education management platform with dual lesson management approaches, advanced booking capabilities, family account support, and extensive notification systems. The modular architecture, role-based access control, and modern UI/UX make it suitable for music schools of various sizes.

For specific implementation details, refer to the specialized documentation:
- [Booking System Guide](BOOKING_SYSTEM_GUIDE.md) - Dynamic booking details
- [Project Summary](PROJECT_SUMMARY.md) - Complete architecture
- [Implementation Checklist](../IMPLEMENTATION_CHECKLIST.md) - Feature verification

---

**Last Updated**: August 19, 2026  
**Version**: 3.0 (Complete System Features)  
**Status**: Production Ready