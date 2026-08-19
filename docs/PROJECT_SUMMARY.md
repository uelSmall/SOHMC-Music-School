# SOHMC Music School — Complete Project Architecture

## Purpose

A comprehensive Laravel 12 music school management system featuring dual lesson management approaches: static lesson content with assignment tracking, and a dynamic booking/scheduling system with teacher availability, lesson requests, and calendar integration. The system supports multiple user roles (Admin, Teacher, Student, Parent) with role-based dashboards, comprehensive notification systems, and family account management.

## Tech Stack

### Backend
- **PHP**: 8.2+
- **Framework**: Laravel 12
- **Real-time UI**: Livewire v3.4
- **Authentication**: Laravel Breeze + Socialite
- **Permissions**: Spatie Laravel Permission (RBAC)
- **File Management**: Spatie Media Library
- **Activity Logging**: Spatie Activity Log
- **Backup**: Spatie Laravel Backup

### Frontend
- **Styling**: TailwindCSS 4.0
- **Build Tool**: Vite 7.0
- **JavaScript**: Alpine.js
- **Calendar**: FullCalendar 6.1
- **UI Framework**: Bootstrap 5.3
- **Icons**: FontAwesome Free

### Database
- **System**: MySQL/PostgreSQL compatible
- **Migrations**: 20+ schema migrations
- **Seeders**: 6 comprehensive seeders
- **Relationships**: Complex many-to-many and polymorphic relationships

## System Architecture

### Modular Structure

The application follows a modular architecture with domain separation:

```
SOHMC-Music-School/
├── Modules/
│   ├── Lesson/           # Static lesson content and assignments
│   ├── Booking/          # Dynamic booking and scheduling system
│   ├── Category/         # Content categorization
│   ├── Menu/             # Dynamic navigation management
│   └── Assignment/       # Assignment management (legacy)
├── app/
│   ├── Http/Controllers/ # Core application controllers
│   ├── Livewire/         # Real-time UI components
│   ├── Models/           # Eloquent models
│   ├── Notifications/    # Notification classes
│   └── Events/           # Event system
├── database/
│   ├── migrations/       # Database schema
│   ├── seeders/          # Demo data generation
│   └── factories/        # Model factories
├── resources/
│   ├── views/            # Blade templates
│   └── js/               # Frontend JavaScript
└── routes/
    └── web.php           # Route definitions
```

### Core Modules

#### 1. Lesson Module (`Modules/Lesson/`)
**Purpose**: Static lesson content management with student assignments and progress tracking.

**Key Components**:
- **Models**: `Lesson`, `LessonStudentAssignment`, `LessonAssignmentComment`
- **Enums**: `LessonStatus`, `AssignmentStatus`
- **Controllers**: `LessonController`
- **Migrations**: Lessons table, assignments table, comments table

**Features**:
- Teacher-owned lesson creation
- Student assignment with status pipeline
- Progress tracking (assigned → started → in_progress → completed)
- Assignment comments and teacher feedback
- File attachments and materials
- Global notes for all assigned students

#### 2. Booking Module (`Modules/Booking/`)
**Purpose**: Dynamic lesson scheduling with teacher availability and request workflow.

**Key Components**:
- **Models**: `LessonRequest`, `BookedLesson`, `Instrument`, `TeacherAvailability`
- **Enums**: `LessonRequestStatus`, `LessonStatus` (booking)
- **Controllers**: Student/Teacher lesson request and management controllers
- **Migrations**: 7 comprehensive migrations for booking system

**Features**:
- Lesson request workflow with teacher suggestions
- Teacher availability management
- Complex booking state machine
- Calendar integration with FullCalendar
- Lesson lifecycle tracking (scheduled/completed/cancelled)
- Rescheduling and cancellation with reasons
- Instrument-based assignment system

#### 3. Category Module (`Modules/Category/`)
**Purpose**: Content categorization and organization.

**Key Components**:
- **Models**: `Category`
- **Controllers**: Backend and frontend category controllers
- **Views**: Category management interfaces

#### 4. Menu Module (`Modules/Menu/`)
**Purpose**: Dynamic navigation menu management.

**Key Components**:
- **Models**: `Menu`, `MenuItem`
- **Livewire**: `MenuItemComponent`
- **Commands**: Menu seeding and cache management

## Database Schema

### Core Tables

#### Users & Authentication
- **users**: Extended user model with parent-student relationships
- **user_providers**: Social authentication providers
- **permissions**: Spatie permission system
- **roles**: User roles (super admin, administrator, teacher, student, parent)
- **model_has_permissions**: User-permission relationships
- **model_has_roles**: User-role relationships
- **role_has_permissions**: Role-permission relationships

#### Lesson System
- **lessons**: Static lesson content with instruments and global notes
- **lesson_student**: Legacy lesson-student pivot table
- **lesson_student_assignments**: Assignment tracking with status pipeline
- **lesson_assignment_comments**: Teacher comments on assignments

#### Booking System
- **instruments**: Normalized instrument management
- **instrument_teacher**: Teacher-instrument many-to-many relationships
- **teacher_availability**: Weekly availability windows
- **lesson_requests**: Lesson request workflow with suggestions
- **booked_lessons**: Scheduled lessons with lifecycle tracking

#### Family System
- **parent_student**: Parent-child relationships for family accounts

#### System Tables
- **settings**: Application configuration
- **notifications**: User notifications
- **activity_log**: System activity tracking
- **media**: Spatie media library
- **gallery_items**: Public gallery management
- **cache, jobs, sessions**: Laravel system tables

## User Roles & Permissions

### Role Hierarchy

1. **Super Admin**: Full system access, user management, system configuration
2. **Administrator**: Backend management, content management, user oversight
3. **Teacher**: Lesson creation, assignment management, booking management
4. **Student**: View lessons, update progress, request bookings
5. **Parent**: Monitor children's lessons and progress

### Key Permissions

#### Lesson Management
- `view_lessons`: View lesson content
- `create_lessons`: Create new lessons
- `edit_lessons`: Edit existing lessons
- `delete_lessons`: Delete lessons
- `assign_lessons`: Assign lessons to students
- `view_assigned_lessons`: View assigned lessons

#### Booking Management
- `manage_lessons`: Manage booking system
- `create_bookings`: Create lesson bookings
- `reschedule_bookings`: Reschedule booked lessons
- `cancel_bookings`: Cancel bookings

#### System Management
- `view_backend`: Access backend interface
- `edit_settings`: Modify system settings
- `manage_users`: User management
- `block_users`: Block/unblock users

## Key Features

### 1. Dual Lesson Management System

#### Static Lesson System
- Teachers create comprehensive lesson content
- Assign lessons to specific students
- Track student progress through status pipeline
- Add teacher comments and feedback
- Attach files and materials
- Set global notes for all assigned students

#### Dynamic Booking System
- Students request lessons with preferred times
- Teachers manage weekly availability
- Complex request workflow with suggestions
- Calendar integration for visual scheduling
- Full lifecycle tracking (scheduled/completed/cancelled)
- Rescheduling and cancellation management

### 2. Assignment Progress Tracking

**Status Pipeline**:
```
assigned → started → in_progress → completed
```

**Features**:
- Color-coded status badges
- Student progress buttons
- Teacher progress dashboard
- Due date management
- Assignment comments system
- Real-time status updates

### 3. Advanced Instrument Management

**Basic System**:
- Simple text-based instrument field in lessons
- Support for piano, guitar, vocals, percussion, etc.

**Advanced System**:
- Normalized instrument table with descriptions
- Teacher-instrument many-to-many relationships
- Active/inactive instrument states
- 8 default instruments with auto-assignment
- Instrument-based availability and booking

### 4. Family Account System

**Features**:
- Parent-student relationships
- Parent dashboard for monitoring children
- View children's assignments and progress
- Family account management
- Role-based access to children's data

### 5. Comprehensive Notification System

**Assignment Notifications**:
- New assignment alerts
- Status update notifications
- Progress completion alerts

**Booking Notifications**:
- New lesson request alerts
- Booking confirmations
- Rejection notifications
- Rescheduling alerts
- Cancellation notifications
- Teacher suggestion accepted alerts

**System Notifications**:
- Account creation notifications
- Registration alerts
- System announcements

### 6. Calendar Integration

**Features**:
- FullCalendar 6.1 integration
- Teacher calendar with scheduled lessons and pending requests
- Student calendar with upcoming lessons and assignment due dates
- Drag-and-drop event management
- Interactive event clicking
- Responsive design

### 7. Role-Based Dashboards

**Teacher Dashboard**:
- Lesson statistics (total, published)
- Assignment tracking (total, due soon)
- Booking management stats (today, upcoming, completed, cancelled)
- Progress tracking overview
- Pending lesson requests
- Upcoming assignments
- Today's lessons list
- Notifications panel

**Student Dashboard**:
- Assignment statistics (total, by status)
- Upcoming assignments with due dates
- Next lesson to work on
- Progress overview
- Upcoming booked lessons
- Notifications panel

**Parent Dashboard**:
- Children's lesson overview
- Progress monitoring
- Upcoming lessons and assignments
- Notifications panel

**Admin Dashboard**:
- System overview
- User management
- Content management
- System configuration
- Backup management

## API & Routes

### Core Routes

#### Student Routes
- `/student/dashboard` - Student dashboard
- `/lessons` - Lesson browsing and search
- `/lessons/{lesson}` - Lesson details
- `/student/lesson-requests` - Lesson request management
- `/student/booking-management` - Booked lessons
- `/student/calendar/events` - Calendar API

#### Teacher Routes
- `/teacher/dashboard` - Teacher dashboard
- `/teacher/assignments` - Assignment management
- `/teacher/lesson-requests` - Request management
- `/teacher/booking-management` - Booking management
- `/teacher/calendar/events` - Calendar API

#### Admin Routes
- `/admin` - Admin dashboard
- `/admin/users` - User management
- `/admin/roles` - Role management
- `/admin/settings` - System settings
- `/admin/backups` - Backup management

#### Parent Routes
- `/parent/dashboard` - Parent dashboard
- `/lessons` - Children's lessons view

## Development Workflow

### Setup & Installation

```bash
# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed

# Build assets
npm run build

# Development server
php artisan serve
npm run dev
```

### Database Seeding

```bash
# Fresh database with all seeders
php artisan migrate:fresh --seed

# Individual seeders
php artisan db:seed --class=AuthTableSeeder
php artisan db:seed --class=BookingInstrumentSeeder
php artisan db:seed --class=LessonSeeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=ParentStudentSeeder
```

### Asset Management

```bash
# Development build with hot reload
npm run dev

# Production build
npm run build

# Format code
composer pint
```

## Code Standards & Best Practices

### Architecture Patterns
- **Modular Design**: Domain separation in Modules/
- **Repository Pattern**: Model scopes for business logic
- **Service Layer**: Controllers handle HTTP, models handle business logic
- **Dependency Injection**: Laravel's automatic dependency injection
- **Event-Driven**: Notification system via Laravel events

### Code Quality
- **PSR-12**: PHP coding standards
- **Type Hints**: Full type coverage on methods
- **Return Types**: Explicit return type declarations
- **Validation**: Form Request classes for validation
- **Error Handling**: Comprehensive exception handling

### Security
- **Authentication**: Laravel's built-in authentication
- **Authorization**: Spatie permissions with middleware
- **CSRF Protection**: Built-in CSRF tokens
- **SQL Injection Prevention**: Eloquent ORM parameter binding
- **XSS Protection**: Blade template auto-escaping
- **Input Validation**: Server-side validation on all inputs

### Performance
- **Eager Loading**: Prevent N+1 query problems
- **Database Indexing**: Strategic indexes on frequently queried columns
- **Query Optimization**: Efficient query construction
- **Pagination**: Large dataset pagination
- **Caching**: Permission caching for performance
- **Asset Optimization**: Vite for optimized asset bundling

## Demo Accounts

### Access Credentials
- **Teacher**: `teacher1@example.com` / `password`
- **Student**: `student1@example.com` / `password`
- **Admin**: `admin@admin.com` / `password`
- **Parent**: `parent1@example.com` / `password` (when seeded)

### Demo Data
- 6 lessons with various instruments
- 8 instruments in booking system
- Sample assignments with different statuses
- Family account relationships
- Gallery items
- System notifications

## Key Files Reference

### Models
- `app/Models/User.php` - Extended user with relationships
- `Modules/Lesson/Models/Lesson.php` - Lesson content model
- `Modules/Lesson/Models/LessonStudentAssignment.php` - Assignment tracking
- `Modules/Booking/Models/LessonRequest.php` - Booking requests
- `Modules/Booking/Models/BookedLesson.php` - Scheduled lessons
- `Modules/Booking/Models/Instrument.php` - Instrument management

### Controllers
- `app/Http/Controllers/LessonController.php` - Lesson content
- `app/Http/Controllers/Teacher/LessonManagementController.php` - Teacher bookings
- `app/Http/Controllers/Student/LessonManagementController.php` - Student bookings
- `app/Http/Controllers/Calendar/LessonCalendarController.php` - Calendar API

### Livewire Components
- `app/Livewire/Teacher/Dashboard.php` - Teacher dashboard
- `app/Livewire/Student/Dashboard.php` - Student dashboard
- `app/Livewire/Backend/Lessons/AssignmentDashboard.php` - Assignment management
- `app/Livewire/Frontend/Lessons/LessonSearch.php` - Lesson search

### Database
- `database/migrations/` - All schema migrations
- `database/seeders/` - Comprehensive seeders
- `Modules/*/Database/migrations/` - Module-specific migrations

## Conventions & Notes

### Modular Development
- New features should be added as modules when appropriate
- Module structure follows Laravel best practices
- Each module has its own models, migrations, and relationships

### Database Naming
- Table names: plural_snake_case
- Foreign keys: {table}_id
- Indexes: idx_{table}_{column}
- Timestamps: created_at, updated_at

### Code Organization
- Controllers: HTTP request handling
- Models: Business logic and relationships
- Livewire: Real-time UI components
- Views: Presentation layer
- Enums: Type-safe constants

### Git Workflow
- Feature branches for new features
- Descriptive commit messages
- Code review before merging
- Tag releases for production deployments

## Pending / Next Improvements

### Short-term
1. Add payment processing for lesson bookings
2. Implement video conferencing integration
3. Add advanced reporting and analytics
4. Implement email template customization
5. Add mobile app API endpoints

### Long-term
1. Multi-school support (white-label)
2. Advanced scheduling algorithms
3. Learning management system integration
4. Mobile applications (iOS/Android)
5. AI-powered lesson recommendations

---

## Documentation Index

- **[README.md](../README.md)** - Quick start and overview
- **[BOOKING_SYSTEM_GUIDE.md](BOOKING_SYSTEM_GUIDE.md)** - Comprehensive booking documentation
- **[FEATURES_GUIDE.md](FEATURES_GUIDE.md)** - Detailed feature documentation
- **[IMPLEMENTATION_CHECKLIST.md](../IMPLEMENTATION_CHECKLIST.md)** - Feature verification
- **[DOCS_INDEX.md](../DOCS_INDEX.md)** - Documentation navigation

---

**Last Updated**: August 19, 2026  
**Version**: 2.0 (Complete System Architecture)  
**Status**: Production Ready