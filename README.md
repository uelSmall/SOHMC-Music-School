# SOHMC Music School — Laravel 12 Management System

A comprehensive Laravel 12 music school management system featuring dual lesson management approaches: static lesson content with assignment tracking, and a dynamic booking/scheduling system with teacher availability, lesson requests, and calendar integration.

## 🎯 System Overview

This is a **modular Laravel 12 application** designed for music schools with:

- **Static Lesson Management**: Teachers create lessons, assign to students, track progress
- **Dynamic Booking System**: Students request lessons, teachers manage availability, real-time scheduling
- **Role-Based Access**: Admin, Teacher, Student, and Parent dashboards
- **Advanced Progress Tracking**: Assignment status pipeline with comments and notifications
- **Calendar Integration**: FullCalendar for visual scheduling and management
- **Family Accounts**: Parent-student relationships for family management

For detailed architecture, see [docs/PROJECT_SUMMARY.md](docs/PROJECT_SUMMARY.md).

## Quick Start

Install dependencies:

```bash
composer install
npm install
```

Build assets (dev):

```bash
npm run dev
```

Build production assets for deployment:

```bash
npm run build
```

Run migrations and seeders:

```bash
php artisan migrate --seed
# or for a clean slate
php artisan migrate:fresh --seed
```

Serve locally:

```bash
php artisan serve
# Visit http://127.0.0.1:8000
```

**Key URLs:**
- Student Lessons: `http://127.0.0.1:8000/lessons`
- Teacher Dashboard: `http://127.0.0.1:8000/teacher/dashboard`
- Student Dashboard: `http://127.0.0.1:8000/student/dashboard`
- Admin Backend: `http://127.0.0.1:8000/admin`
- Booking Management: `http://127.0.0.1:8000/teacher/booking-management`

## Deployment

This repository uses a standard Laravel root layout suitable for various hosting environments including Hostinger, VPS, and cloud platforms.

### Standard Deployment
- Keep the Laravel application at repository root (`app`, `bootstrap`, `config`, `database`, `public`, `resources`, `routes`, `storage`, `artisan`)
- Root `index.php` forwards requests to `public/index.php`
- Root `.htaccess` routes requests through `index.php` for shared hosting compatibility

### Deploy Steps
```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
```

### Environment Configuration
Ensure `.env` is configured with:
- Database credentials
- `APP_URL` set to your domain
- Email configuration for notifications
- File storage settings

## Seeded Demo Accounts

- `teacher1@example.com` / `password` - Teacher with lesson management and booking
- `student1@example.com` / `password` - Student with lesson access and booking requests
- `admin@admin.com` / `password` - Full system administrator
- `parent1@example.com` / `password` - Parent account (when seeded)

## Where To Look

### Core Lesson System
- Lesson model & relations: [Modules/Lesson/Models/Lesson.php](Modules/Lesson/Models/Lesson.php)
- Assignment model & logic: [Modules/Lesson/Models/LessonStudentAssignment.php](Modules/Lesson/Models/LessonStudentAssignment.php)
- Assignment comments: [Modules/Lesson/Models/LessonAssignmentComment.php](Modules/Lesson/Models/LessonAssignmentComment.php)
- Enums: [Modules/Lesson/Enums/](Modules/Lesson/Enums/) (AssignmentStatus, LessonStatus)

### Booking System
- Lesson requests: [Modules/Booking/Models/LessonRequest.php](Modules/Booking/Models/LessonRequest.php)
- Booked lessons: [Modules/Booking/Models/BookedLesson.php](Modules/Booking/Models/BookedLesson.php)
- Teacher availability: [Modules/Booking/Models/TeacherAvailability.php](Modules/Booking/Models/TeacherAvailability.php)
- Instruments: [Modules/Booking/Models/Instrument.php](Modules/Booking/Models/Instrument.php)
- Booking enums: [Modules/Booking/Enums/](Modules/Booking/Enums/) (LessonRequestStatus, LessonStatus)

### Backend Components
- Lesson management: [app/Livewire/Backend/Lessons/](app/Livewire/Backend/Lessons/)
- Assignment management: [app/Livewire/Backend/Assignments/](app/Livewire/Backend/Assignments/)
- Teacher dashboard: [app/Livewire/Teacher/Dashboard.php](app/Livewire/Teacher/Dashboard.php)
- Student dashboard: [app/Livewire/Student/Dashboard.php](app/Livewire/Student/Dashboard.php)
- Parent dashboard: [app/Livewire/Parents/Dashboard.php](app/Livewire/Parents/Dashboard.php)

### Frontend Components
- Student lesson search: [app/Livewire/Frontend/Lessons/LessonSearch.php](app/Livewire/Frontend/Lessons/LessonSearch.php)
- Student progress updates: [app/Livewire/Frontend/Lessons/UpdateStudentAssignmentStatus.php](app/Livewire/Frontend/Lessons/UpdateStudentAssignmentStatus.php)

### Controllers
- Lesson controller: [app/Http/Controllers/LessonController.php](app/Http/Controllers/LessonController.php)
- Teacher booking management: [app/Http/Controllers/Teacher/LessonManagementController.php](app/Http/Controllers/Teacher/LessonManagementController.php)
- Student booking management: [app/Http/Controllers/Student/LessonManagementController.php](app/Http/Controllers/Student/LessonManagementController.php)
- Calendar controller: [app/Http/Controllers/Calendar/LessonCalendarController.php](app/Http/Controllers/Calendar/LessonCalendarController.php)

### Database
- Migrations: [database/migrations/](database/migrations/) & [Modules/*/Database/migrations/](Modules/)
- Seeders: [database/seeders/](database/seeders/) (includes BookingInstrumentSeeder, LessonSeeder, UserSeeder, ParentStudentSeeder)

### Documentation
- Architecture: [docs/PROJECT_SUMMARY.md](docs/PROJECT_SUMMARY.md)
- Features guide: [docs/FEATURES_GUIDE.md](docs/FEATURES_GUIDE.md)
- Documentation index: [DOCS_INDEX.md](DOCS_INDEX.md)

## Key Features

### 📚 Static Lesson Management
✅ **Assignment Tracking** - Track which lessons students are assigned with status (assigned, started, in_progress, completed)
✅ **Assignment Comments** - Teachers can add notes and feedback to student assignments
✅ **Instrument Grouping** - Lessons organized by instrument (Piano, Guitar, Voice, Steelpan, Music Theory, etc.)
✅ **Search & Filtering** - Reactive Livewire search and filters for lessons and assignments
✅ **Progress Tracking** - Color-coded status badges and progress dashboard
✅ **Teacher Dashboard** - Assign lessons, monitor progress, update status, view comments
✅ **Student Dashboard** - View assigned lessons, update progress, filter by status
✅ **File Attachments** - Lessons can include downloadable materials

### 📅 Dynamic Booking System
✅ **Lesson Requests** - Students can request lessons with preferred times
✅ **Teacher Availability** - Teachers set weekly availability windows
✅ **Booking Workflow** - Complex state machine (Pending → Teacher Confirmed → Student Accepted)
✅ **Teacher Suggestions** - Teachers can suggest alternative times for requests
✅ **Rescheduling** - Flexible rescheduling with notification system
✅ **Cancellation** - Proper cancellation workflow with reasons
✅ **Calendar Integration** - FullCalendar for visual scheduling
✅ **Lesson Duration** - Configurable lesson durations (30, 45, 60, 90 minutes)

### 👥 User Management
✅ **Role-Based Access** - Admin, Teacher, Student, Parent roles with Spatie permissions
✅ **Family Accounts** - Parent-student relationships for family management
✅ **Parent Dashboard** - Monitor children's lessons and progress
✅ **Profile Management** - User profiles with avatars and settings
✅ **Social Login** - Optional social authentication via Laravel Socialite

### 🔔 Notifications
✅ **Assignment Notifications** - Status updates, new assignments
✅ **Booking Notifications** - Request confirmations, rejections, rescheduling
✅ **System Notifications** - Account updates, important announcements
✅ **Notification Bell** - Real-time notification indicator in UI

### 🎨 UI/UX
✅ **Modern Design** - TailwindCSS 4.0 with responsive layouts
✅ **Livewire Components** - Reactive UI without page reloads
✅ **Role-Based Dashboards** - Customized interfaces per user type
✅ **Mobile Responsive** - Works seamlessly on all devices
✅ **Accessibility** - ARIA labels, semantic HTML, keyboard navigation  

## Key Routes

### Student Routes
- **Student Dashboard**: `GET /student/dashboard` - Overview of assignments and progress
- **Student Lessons**: `GET /lessons` - Search, filter, and view assigned lessons
- **Lesson Details**: `GET /lessons/{lesson}` - View lesson content and materials
- **Download Materials**: `GET /lessons/{lesson}/download` - Download lesson attachments
- **Lesson Requests**: `GET /student/lesson-requests` - View and create lesson requests
- **Booking Management**: `GET /student/booking-management` - View booked lessons
- **Calendar Events**: `GET /student/calendar/events` - Calendar API for student
- **Profile**: `GET /profile` - User profile management

### Teacher Routes
- **Teacher Dashboard**: `GET /teacher/dashboard` - Overview of lessons, assignments, bookings
- **Teacher Assignments**: `GET /teacher/assignments` - Manage student assignments
- **Booking Management**: `GET /teacher/booking-management` - Manage lesson requests and bookings
- **Lesson Requests**: `GET /teacher/lesson-requests` - View and respond to requests
- **Calendar Events**: `GET /teacher/calendar/events` - Calendar API for teacher
- **Lesson CRUD**: `GET /admin/lessons` - Create/edit/publish lessons (admin access)

### Admin Routes
- **Admin Dashboard**: `GET /admin` - System overview and management
- **Admin Assignments**: `GET /admin/assignments` - Cross-teacher assignment monitoring
- **User Management**: `GET /admin/users` - Manage all users
- **Role Management**: `GET /admin/roles` - Manage roles and permissions
- **Settings**: `GET /admin/settings` - System configuration
- **Backups**: `GET /admin/backups` - Database backup management
- **Gallery**: `GET /admin/gallery-items` - Media gallery management

### Parent Routes
- **Parent Dashboard**: `GET /parent/dashboard` - Monitor children's progress
- **Children's Lessons**: `GET /lessons` - View children's assigned lessons

### Public Routes
- **Home**: `GET /` - Landing page
- **About**: `GET /about` - About page
- **Contact**: `GET /contact` - Contact page
- **Gallery**: `GET /gallery` - Public gallery
- **Auth Routes**: Standard Laravel authentication routes

## Tech Stack

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: TailwindCSS 4.0, Vite 7.0, Alpine.js
- **Real-time UI**: Livewire 3.4
- **Database**: MySQL/PostgreSQL compatible
- **Authentication**: Laravel Breeze + Socialite
- **Permissions**: Spatie Laravel Permission (RBAC)
- **File Management**: Spatie Media Library
- **Calendar**: FullCalendar 6.1
- **Logging**: Spatie Activity Log, Laravel Pail
- **Development**: Laravel Pint (code formatting)

## System Architecture

### Modular Structure
- `Modules/Lesson/` - Static lesson content and assignments
- `Modules/Booking/` - Dynamic booking and scheduling system
- `Modules/Category/` - Content categorization
- `Modules/Menu/` - Dynamic navigation management
- `app/` - Core application logic and Livewire components

### Database Schema
- **Users**: Extended with parent-student relationships
- **Lessons**: Static content with assignments and comments
- **Lesson Student Assignments**: Progress tracking with status pipeline
- **Instruments**: Normalized instrument management
- **Lesson Requests**: Booking request workflow
- **Booked Lessons**: Scheduled lessons with lifecycle management
- **Teacher Availability**: Weekly availability windows
- **Parent-Student**: Family account relationships

## Development Guidelines

### Code Standards
- Follow PSR-12 coding standards
- Use Laravel Pint for code formatting
- Implement proper error handling and validation
- Use type hints and return types
- Write meaningful commit messages

### Testing
- Feature tests for critical workflows
- Unit tests for business logic
- Test database migrations and seeders
- Test role-based access control

### Security
- Never commit secrets or API keys
- Use Laravel's authentication and authorization
- Validate all user inputs
- Use prepared statements (Laravel ORM)
- Implement CSRF protection

## Getting Help

- **Documentation Index**: [DOCS_INDEX.md](DOCS_INDEX.md) - Complete documentation navigation
- **Features Guide**: [docs/FEATURES_GUIDE.md](docs/FEATURES_GUIDE.md) - Detailed feature documentation
- **Project Summary**: [docs/PROJECT_SUMMARY.md](docs/PROJECT_SUMMARY.md) - Architecture overview
- **Implementation Checklist**: [IMPLEMENTATION_CHECKLIST.md](IMPLEMENTATION_CHECKLIST.md) - Feature verification

## Contributing

1. Follow the existing code style and standards
2. Write tests for new features
3. Update documentation for any changes
4. Use meaningful commit messages
5. Test on multiple user roles (admin, teacher, student, parent)

## License

GPL-3.0-or-later - See [LICENSE.md](LICENSE.md) for details

---

This README provides a comprehensive overview of the SOHMC Music School system. For detailed architecture and implementation details, see [docs/PROJECT_SUMMARY.md](docs/PROJECT_SUMMARY.md) and [docs/FEATURES_GUIDE.md](docs/FEATURES_GUIDE.md).
