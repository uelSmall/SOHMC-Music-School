# 📚 Documentation Index

Welcome to the SOHMC Music School project. This guide helps you navigate all documentation for the comprehensive music school management system.

---

## 🎯 Start Here

**New to the project?**
→ Read [README.md](README.md) for a complete system overview.

**Need a quick feature overview?**
→ Read [COMPLETION_SUMMARY.md](COMPLETION_SUMMARY.md) for a 2-minute overview.

**Want to understand the complete architecture?**
→ See [docs/PROJECT_SUMMARY.md](docs/PROJECT_SUMMARY.md) for full system architecture.

**Interested in the booking system?**
→ See [docs/BOOKING_SYSTEM_GUIDE.md](docs/BOOKING_SYSTEM_GUIDE.md) for comprehensive booking documentation.

---

## 📖 Documentation Files

### Project Overview
- **[README.md](README.md)** — Complete system overview with features, tech stack, and getting started
- **[COMPLETION_SUMMARY.md](COMPLETION_SUMMARY.md)** — Quick summary of what was built and how to use it
- **[docs/PROJECT_SUMMARY.md](docs/PROJECT_SUMMARY.md)** — Complete system architecture and technical overview

### Feature Documentation
- **[docs/BOOKING_SYSTEM_GUIDE.md](docs/BOOKING_SYSTEM_GUIDE.md)** — Comprehensive booking and scheduling system guide
- **[docs/FEATURES_GUIDE.md](docs/FEATURES_GUIDE.md)** — Detailed feature documentation and workflows
- **[IMPLEMENTATION_CHECKLIST.md](IMPLEMENTATION_CHECKLIST.md)** — Complete feature verification checklist

### Implementation Details
- **[IMPLEMENTATION_REPORT.md](IMPLEMENTATION_REPORT.md)** — Technical report with architecture, decisions, and deployment

### Setup & Usage
- **[QUICKSTART.sh](QUICKSTART.sh)** — Automated setup script

---

## 🗂️ Key Directories

### Core Modules
```
Modules/
  ├── Lesson/           # Static lesson content and assignments
  │   ├── Models/
  │   │   ├── Lesson.php
  │   │   ├── LessonStudentAssignment.php
  │   │   └── LessonAssignmentComment.php
  │   ├── Enums/
  │   │   ├── LessonStatus.php
  │   │   └── AssignmentStatus.php
  │   ├── Http/Controllers/
  │   │   └── LessonController.php
  │   └── Database/migrations/
  ├── Booking/          # Dynamic booking and scheduling system
  │   ├── Models/
  │   │   ├── LessonRequest.php
  │   │   ├── BookedLesson.php
  │   │   ├── Instrument.php
  │   │   └── TeacherAvailability.php
  │   ├── Enums/
  │   │   ├── LessonRequestStatus.php
  │   │   └── LessonStatus.php
  │   └── Database/migrations/
  ├── Category/         # Content categorization
  └── Menu/             # Dynamic navigation management
```

### Application Structure
```
app/
  ├── Http/Controllers/
  │   ├── LessonController.php
  │   ├── Teacher/
  │   │   ├── LessonManagementController.php
  │   │   └── LessonRequestController.php
  │   ├── Student/
  │   │   ├── LessonManagementController.php
  │   │   └── LessonRequestController.php
  │   └── Calendar/
  │       └── LessonCalendarController.php
  ├── Livewire/
  │   ├── Teacher/Dashboard.php
  │   ├── Student/Dashboard.php
  │   ├── Parents/Dashboard.php
  │   ├── Backend/Lessons/
  │   └── Frontend/Lessons/
  ├── Models/
  │   └── User.php (extended with relationships)
  └── Notifications/ (10+ notification classes)
```

### Database
```
database/
  ├── migrations/ (20+ schema migrations)
  └── seeders/
      ├── AuthTableSeeder.php
      ├── BookingInstrumentSeeder.php
      ├── LessonSeeder.php
      ├── UserSeeder.php
      └── ParentStudentSeeder.php
```

---

## 🚀 Quick Commands

### Setup
```bash
composer install && npm install
php artisan migrate --seed
npm run build
```

### Development
```bash
php artisan serve          # Terminal 1
npm run dev                # Terminal 2
```

### Maintenance
```bash
php artisan migrate:fresh --seed    # Reset DB
php artisan db:seed --class=LessonSeeder  # Reseed lessons
npm run build               # Build for production
composer pint               # Format code
```

---

## 👥 Demo Accounts

| Role | Email | Password |
|------|-------|----------|
| Student | `student1@example.com` | `password` |
| Teacher | `teacher1@example.com` | `password` |
| Admin | `admin@admin.com` | `password` |

---

## 🔗 Main Routes

| Route | Purpose | User Roles |
|-------|---------|-----------|
| `/lessons` | Student dashboard (search/filter) | All authenticated users |
| `/admin/assignments` | Teacher dashboard (assign & monitor) | Teachers & Admins |
| `/admin/lessons` | Lesson admin CRUD | Admins & Teachers |
| `/` | Home page | Public |

---

## 💡 Common Tasks

### Add a New Lesson
1. Log in as admin/teacher
2. Visit `/admin/lessons`
3. Click "Create" button
4. Fill form (title, description, teacher, instrument, status)
5. Save

### Assign Lessons to Students
1. Log in as teacher
2. Visit `/admin/assignments`
3. Click "+ Assign Lesson"
4. Select lesson, students, optional due date
5. Submit

### View Assigned Lessons (as Student)
1. Log in as student
2. Visit `/lessons`
3. Use search/filters/tabs to find lessons
4. Click status buttons to update progress

---

## 🔍 Search This Codebase

### Models & Relationships
- `Lesson` model: `Modules/Lesson/Models/Lesson.php`
- `LessonStudentAssignment` model: `Modules/Lesson/Models/LessonStudentAssignment.php`
- `User` model: `app/Models/User.php`

### Enums
- `LessonStatus`: `Modules/Lesson/Enums/LessonStatus.php`
- `AssignmentStatus`: `Modules/Lesson/Enums/AssignmentStatus.php`

### Livewire Components
- Search/Filter: `app/Livewire/Frontend/Lessons/LessonSearch.php`
- Teacher Dashboard: `app/Livewire/Backend/Lessons/AssignmentDashboard.php`
- Assignment Modal: `app/Livewire/Backend/Assignments/AssignLessonModal.php`

### Blade Views
- Student Dashboard: `resources/views/frontend/lessons/lesson-search.blade.php`
- Teacher Dashboard: `resources/views/backend/lessons/assignments-dashboard.blade.php`
- Main Lessons Page: `resources/views/lessons/index.blade.php`

---

## 📊 Features at a Glance

### 📚 Static Lesson Management
✅ **Assignments**: Track which lessons students are assigned with status  
✅ **Assignment Comments**: Teachers can add notes and feedback to assignments  
✅ **Instruments**: Lessons organized by instrument (Piano, Guitar, Voice, Steelpan, etc.)  
✅ **Search & Filters**: Reactive Livewire search, instrument filter, status tabs  
✅ **Progress Tracking**: Color-coded badges and progress buttons  
✅ **File Attachments**: Lessons can include downloadable materials  
✅ **Global Notes**: Teacher notes visible to all assigned students  

### 📅 Dynamic Booking System
✅ **Lesson Requests**: Students can request lessons with preferred times  
✅ **Teacher Availability**: Teachers set weekly availability windows  
✅ **Booking Workflow**: Complex state machine (Pending → Teacher Confirmed → Student Accepted)  
✅ **Teacher Suggestions**: Teachers can suggest alternative times  
✅ **Rescheduling**: Flexible rescheduling with notification system  
✅ **Cancellation**: Proper cancellation workflow with reasons  
✅ **Calendar Integration**: FullCalendar for visual scheduling  
✅ **Lesson Duration**: Configurable lesson durations (30, 45, 60, 90 minutes)  

### 👥 User Management
✅ **Role-Based Access**: Admin, Teacher, Student, Parent roles with Spatie permissions  
✅ **Family Accounts**: Parent-student relationships for family management  
✅ **Parent Dashboard**: Monitor children's lessons and progress  
✅ **Profile Management**: User profiles with avatars and settings  
✅ **Social Login**: Optional social authentication via Laravel Socialite  

### 🔔 Notifications
✅ **Assignment Notifications**: Status updates, new assignments  
✅ **Booking Notifications**: Request confirmations, rejections, rescheduling  
✅ **System Notifications**: Account updates, important announcements  
✅ **Notification Bell**: Real-time notification indicator in UI  

### 🎨 UI/UX
✅ **Modern Design**: TailwindCSS 4.0 with responsive layouts  
✅ **Livewire Components**: Reactive UI without page reloads  
✅ **Role-Based Dashboards**: Customized interfaces per user type  
✅ **Mobile Responsive**: Works seamlessly on all devices  
✅ **Accessibility**: ARIA labels, semantic HTML, keyboard navigation  

---

## 🔗 Main Routes

### Student Routes
|| Route | Purpose | User Roles |
||-------|---------|-----------|
|| `/student/dashboard` | Student dashboard overview | Students |
|| `/lessons` | Student lesson search and browsing | Students, Parents, Teachers |
|| `/lessons/{lesson}` | View lesson details and materials | Students, Teachers |
|| `/student/lesson-requests` | Create and view lesson requests | Students |
|| `/student/booking-management` | View booked lessons | Students |
|| `/student/calendar/events` | Calendar API for students | Students |

### Teacher Routes
|| Route | Purpose | User Roles |
||-------|---------|-----------|
|| `/teacher/dashboard` | Teacher dashboard overview | Teachers |
|| `/teacher/assignments` | Assignment management | Teachers |
|| `/teacher/lesson-requests` | View and respond to requests | Teachers |
|| `/teacher/booking-management` | Manage booked lessons | Teachers |
|| `/teacher/calendar/events` | Calendar API for teachers | Teachers |

### Admin Routes
|| Route | Purpose | User Roles |
||-------|---------|-----------|
|| `/admin` | Admin dashboard | Admins |
|| `/admin/users` | User management | Admins |
|| `/admin/roles` | Role management | Admins |
|| `/admin/settings` | System configuration | Admins |
|| `/admin/backups` | Database backup management | Admins |

### Parent Routes
|| Route | Purpose | User Roles |
||-------|---------|-----------|
|| `/parent/dashboard` | Parent dashboard for monitoring children | Parents |

### Public Routes
|| Route | Purpose | User Roles |
||-------|---------|-----------|
|| `/` | Home page | Public |
|| `/about` | About page | Public |
|| `/contact` | Contact page | Public |
|| `/gallery` | Public gallery | Public |

---

## 🎓 Learning Resources

### Architecture & Design
- Read [docs/FEATURES_GUIDE.md](docs/FEATURES_GUIDE.md) Section: "Architecture Overview"
- See "Code Standards Applied" section for best practices

### Understanding the Code
1. Start with models: `Lesson.php` → `LessonStudentAssignment.php`
2. Check relationships and scopes
3. Look at Livewire components (they orchestrate the UI logic)
4. Review Blade views (they render the HTML)

### Extending the System
- See "Future Enhancements" in [IMPLEMENTATION_REPORT.md](IMPLEMENTATION_REPORT.md)
- Review "Recommended Next Steps" for prioritized features

---

## ❓ FAQ

**Q: What's the difference between static lessons and dynamic booking?**  
A: Static lessons are teacher-created content that students work through at their own pace with assignments. Dynamic booking is a scheduling system where students request specific lesson times with teachers.

**Q: How does the booking workflow work?**  
A: Students submit lesson requests with preferred times → Teachers can confirm, reject, or suggest alternative times → Students accept suggestions to finalize bookings → Lessons appear in both parties' calendars.

**Q: How do I add a new instrument?**  
A: Use the booking system's `Instrument` model. Run `php artisan db:seed --class=BookingInstrumentSeeder` to see the format, or add instruments via the database/instrument seeder.

**Q: Can parents manage their children's accounts?**  
A: Yes, parents can monitor their children's lessons, view progress, and see upcoming lessons through the parent dashboard. They cannot directly modify assignments but have full visibility.

**Q: How do the notifications work?**  
A: The system uses Laravel's notification system with database and email channels. Key events (assignments, booking changes, confirmations) trigger automatic notifications to relevant parties.

**Q: Can I use this with a different database?**  
A: Yes. Update `.env` with your database credentials. Migrations use Laravel's schema builder (compatible with PostgreSQL, MySQL, SQLite).

**Q: How do I customize the calendar integration?**  
A: The calendar uses FullCalendar 6.1. Customize the JavaScript in your views and modify the calendar controller methods to change event data and behavior.

**Q: What if I want to add more booking statuses?**  
A: Add cases to `LessonRequestStatus` enum in `Modules/Booking/Enums/`, update any relevant validation rules, and ensure your notification system handles the new status.

---

## 🆘 Support

### Still Have Questions?

1. **Check the documentation**:
   - [docs/PROJECT_SUMMARY.md](docs/PROJECT_SUMMARY.md) — Complete system architecture
   - [docs/BOOKING_SYSTEM_GUIDE.md](docs/BOOKING_SYSTEM_GUIDE.md) — Booking system details
   - [docs/FEATURES_GUIDE.md](docs/FEATURES_GUIDE.md) — Feature workflows
   - [IMPLEMENTATION_CHECKLIST.md](IMPLEMENTATION_CHECKLIST.md) — Feature verification

2. **Review the code**:
   - In-code comments explain complex logic
   - Blade views have structural comments
   - Models have relationship documentation

3. **Check diagnostics**:
   - Run `php artisan route:list` to see all routes
   - Verify migrations: `php artisan migrate:status`
   - Check seeders: `php artisan db:seed --list`

---

## 📝 Documentation Standards

All documentation follows these principles:
- **Clear**: Use simple, non-technical language
- **Complete**: Include examples and edge cases
- **Concrete**: Show actual code when possible
- **Consistent**: Use same terminology throughout

---

## 🎉 You're All Set!

The SOHMC Music School system is fully implemented with comprehensive documentation. Start by reading [README.md](README.md) for a complete overview, then explore [docs/PROJECT_SUMMARY.md](docs/PROJECT_SUMMARY.md) for architecture details, and [docs/BOOKING_SYSTEM_GUIDE.md](docs/BOOKING_SYSTEM_GUIDE.md) for the booking system specifics.

**Happy coding!** 🚀
