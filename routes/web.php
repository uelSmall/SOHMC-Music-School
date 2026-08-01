<?php

use App\Http\Controllers\Backend\BackendController;
use App\Http\Controllers\Backend\BackupController;
use App\Http\Controllers\Backend\NotificationsController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\UserController as BackendUserController;
use App\Http\Controllers\Backend\GalleryItemController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\UserController as FrontendUserController;
use App\Http\Controllers\LanguageController;
use App\Livewire\Frontend\Home;
use App\Livewire\Frontend\Privacy;
use App\Livewire\Frontend\Terms;
use App\Livewire\Frontend\Users\ChangePassword;
use App\Livewire\Frontend\Users\Profile;
use App\Livewire\Frontend\Users\ProfileEdit;
use App\Livewire\Student\Dashboard as StudentDashboard;
use App\Livewire\Parents\Dashboard as ParentDashboard;
use App\Livewire\Teacher\Dashboard as TeacherDashboard;
use Illuminate\Support\Facades\Route;

/*
*
* Auth Routes
*
* --------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

/*
*
* Frontend Routes
*
* --------------------------------------------------------------------
*/

// home route
Route::get('home', Home::class)->name('home');

// Language Switch
Route::get('language/{language}', [LanguageController::class, 'switch'])->name('language.switch');

Route::get('dashboard', function () {
    if (! auth()->check()) {
        return redirect()->route('frontend.index');
    }

    return redirect()->route(auth()->user()->dashboardRouteName());
})->name('dashboard');

// pages
Route::get('terms', Terms::class)->name('terms');
Route::get('privacy', Privacy::class)->name('privacy');

Route::group(['as' => 'frontend.'], function () {
    Route::get('/', Home::class)->name('index');
    Route::view('about', 'frontend.about')->name('about');
    Route::get('gallery', [GalleryController::class, 'index'])->name('gallery');
    Route::view('contact', 'frontend.contact')->name('contact');

    Route::group(['middleware' => ['auth']], function () {
        /*
        *
        *  Users Routes
        *
        * ---------------------------------------------------------------------
        */
        $module_name = 'users';
        Route::get('profile/edit', ProfileEdit::class)->name("{$module_name}.profileEdit");
        Route::get('profile/changePassword', ChangePassword::class)->name("{$module_name}.changePassword");
        Route::get('profile/{username?}', Profile::class)->name("{$module_name}.profile");

        // Keep these as controller routes for now (POST/PATCH/DELETE methods)
        Route::get("{$module_name}/emailConfirmationResend", [FrontendUserController::class, 'emailConfirmationResend'])->name("{$module_name}.emailConfirmationResend");
        Route::delete("{$module_name}/userProviderDestroy", [FrontendUserController::class, 'userProviderDestroy'])->name("{$module_name}.userProviderDestroy");
    });
});

/*
*
* Backend Routes
* These routes need view-backend permission
* --------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin', 'as' => 'backend.', 'middleware' => ['auth', 'can:view_backend']], function () {
    /**
     * Backend Dashboard
     * Namespaces indicate folder structure.
     */
    Route::get('/', [BackendController::class, 'index'])->name('home');
    Route::get('dashboard', [BackendController::class, 'index'])->name('dashboard');

    /*
     *
     *  Settings Routes
     *
     * ---------------------------------------------------------------------
     */
    Route::group(['middleware' => ['can:edit_settings']], function () {
        $module_name = 'settings';
        Route::get("{$module_name}", [SettingController::class, 'index'])->name("{$module_name}.index");
        Route::post("{$module_name}", [SettingController::class, 'store'])->name("{$module_name}.store");
    });

    Route::group(['middleware' => ['can:edit_backend']], function () {
        Route::resource('gallery-items', GalleryItemController::class)->except(['show']);
        Route::patch('gallery-items/{galleryItem}/move/{direction}', [GalleryItemController::class, 'move'])
            ->where('direction', 'up|down')
            ->name('gallery-items.move');
    });

    /*
     *
     *  Notification Routes
     *
     * ---------------------------------------------------------------------
     */
    $module_name = 'notifications';
    Route::get("{$module_name}", [NotificationsController::class, 'index'])->name("{$module_name}.index");
    Route::get("{$module_name}/markAllAsRead", [NotificationsController::class, 'markAllAsRead'])->name("{$module_name}.markAllAsRead");
    Route::delete("{$module_name}/deleteAll", [NotificationsController::class, 'deleteAll'])->name("{$module_name}.deleteAll");
    Route::get("{$module_name}/{id}", [NotificationsController::class, 'show'])->name("{$module_name}.show");

    /*
     *
     *  Backup Routes
     *
     * ---------------------------------------------------------------------
     */
    $module_name = 'backups';
    Route::get("{$module_name}", [BackupController::class, 'index'])->name("{$module_name}.index");
    Route::get("{$module_name}/create", [BackupController::class, 'create'])->name("{$module_name}.create");
    Route::get("{$module_name}/download/{file_name}", [BackupController::class, 'download'])->name("{$module_name}.download");
    Route::get("{$module_name}/delete/{file_name}", [BackupController::class, 'delete'])->name("{$module_name}.delete");

    /*
     *
     *  Roles Routes
     *
     * ---------------------------------------------------------------------
     */
    $module_name = 'roles';
    Route::resource("{$module_name}", RolesController::class);

    /*
     *
     *  Users Routes
     *
     * ---------------------------------------------------------------------
     */
    $module_name = 'users';
    Route::get("{$module_name}/{id}/resend-email-confirmation", [BackendUserController::class, 'emailConfirmationResend'])->name("{$module_name}.emailConfirmationResend");
    Route::delete("{$module_name}/user-provider-destroy", [BackendUserController::class, 'userProviderDestroy'])->name("{$module_name}.userProviderDestroy");
    Route::get("{$module_name}/{id}/change-password", [BackendUserController::class, 'changePassword'])->name("{$module_name}.changePassword");
    Route::patch("{$module_name}/{id}/change-password", [BackendUserController::class, 'changePasswordUpdate'])->name("{$module_name}.changePasswordUpdate");
    Route::get("{$module_name}/trashed", [BackendUserController::class, 'trashed'])->name("{$module_name}.trashed");
    Route::patch("{$module_name}/{id}/trashed", [BackendUserController::class, 'restore'])->name("{$module_name}.restore");
    Route::get("{$module_name}/index_data", [BackendUserController::class, 'index_data'])->name("{$module_name}.index_data");
    Route::get("{$module_name}/index_list", [BackendUserController::class, 'index_list'])->name("{$module_name}.index_list");
    Route::patch("{$module_name}/{id}/block", [BackendUserController::class, 'block'])->name("{$module_name}.block")->middleware('can:block_users');
    Route::patch("{$module_name}/{id}/unblock", [BackendUserController::class, 'unblock'])->name("{$module_name}.unblock")->middleware('can:block_users');
    Route::resource("{$module_name}", BackendUserController::class);
});

/*
*
* Teacher Routes
*
* --------------------------------------------------------------------
*/
Route::prefix('teacher')->as('teacher.')->middleware(['auth', 'can:manage_lessons'])->group(function () {
    Route::get('/dashboard', TeacherDashboard::class)->name('dashboard');
    Route::get('/calendar/events', [\App\Http\Controllers\Calendar\LessonCalendarController::class, 'teacherEvents'])
        ->name('calendar.events')
        ->middleware('role:teacher');
    Route::get('/lesson-management', [\App\Http\Controllers\Teacher\LessonManagementController::class, 'index'])
        ->name('lesson-management.index')
        ->middleware('role:teacher');
    Route::get('/lesson-management/{lesson}', [\App\Http\Controllers\Teacher\LessonManagementController::class, 'show'])
        ->name('lesson-management.show')
        ->middleware('role:teacher');
    Route::patch('/lesson-management/{lesson}/complete', [\App\Http\Controllers\Teacher\LessonManagementController::class, 'complete'])
        ->name('lesson-management.complete')
        ->middleware('role:teacher');
    Route::patch('/lesson-management/{lesson}/cancel', [\App\Http\Controllers\Teacher\LessonManagementController::class, 'cancel'])
        ->name('lesson-management.cancel')
        ->middleware('role:teacher');
    Route::patch('/lesson-management/{lesson}/reschedule', [\App\Http\Controllers\Teacher\LessonManagementController::class, 'reschedule'])
        ->name('lesson-management.reschedule')
        ->middleware('role:teacher');
    Route::get('/assignments', \App\Livewire\Backend\Lessons\AssignmentDashboard::class)
        ->name('assignments.index')
        ->middleware('can:assign_lessons');

    Route::resource('lesson-requests', \App\Http\Controllers\Teacher\LessonRequestController::class)
        ->only(['index', 'show'])
        ->parameters(['lesson-requests' => 'lessonRequest'])
        ->middleware('role:teacher');

    Route::patch('lesson-requests/{lessonRequest}/confirm', [\App\Http\Controllers\Teacher\LessonRequestController::class, 'confirm'])
        ->name('lesson-requests.confirm')
        ->middleware('role:teacher');

    Route::patch('lesson-requests/{lessonRequest}/reschedule', [\App\Http\Controllers\Teacher\LessonRequestController::class, 'reschedule'])
        ->name('lesson-requests.reschedule')
        ->middleware('role:teacher');

    Route::patch('lesson-requests/{lessonRequest}/reject', [\App\Http\Controllers\Teacher\LessonRequestController::class, 'reject'])
        ->name('lesson-requests.reject')
        ->middleware('role:teacher');
});

/*
*
* Parent Routes
*
* --------------------------------------------------------------------
*/
Route::prefix('parent')->as('parent.')->middleware(['auth', 'role:parent'])->group(function () {
    Route::get('/dashboard', ParentDashboard::class)->name('dashboard');
});

/*
*
* Student Routes
*
* --------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/student/dashboard', StudentDashboard::class)
        ->name('student.dashboard')
        ->middleware('can:view_assigned_lessons');
    Route::get('/student/lesson-management', [\App\Http\Controllers\Student\LessonManagementController::class, 'index'])
        ->name('student.lesson-management.index')
        ->middleware('role:student');
    Route::get('/student/lesson-management/{lesson}', [\App\Http\Controllers\Student\LessonManagementController::class, 'show'])
        ->name('student.lesson-management.show')
        ->middleware('role:student');

    Route::get('/student/lessons', [\App\Http\Controllers\LessonController::class, 'index'])
        ->name('student.lessons.index')
        ->middleware('can:view_assigned_lessons');

    Route::get('/lessons', [\App\Http\Controllers\LessonController::class, 'index'])
        ->name('lessons.index')
        ->middleware('can:view_assigned_lessons');

    Route::get('/lessons/{lesson}', [\App\Http\Controllers\LessonController::class, 'show'])
        ->name('lessons.show')
        ->middleware('can:view_assigned_lessons');

    Route::get('/lessons/{lesson}/download', [\App\Http\Controllers\LessonController::class, 'download'])
        ->name('lessons.download')
        ->middleware('can:view_assigned_lessons');

    Route::get('/lessons/{lesson}/preview', [\App\Http\Controllers\LessonController::class, 'preview'])
        ->name('lessons.preview')
        ->middleware('can:view_assigned_lessons');

    Route::post('/lessons/{lesson}/start', [\App\Http\Controllers\LessonController::class, 'markAsStarted'])
        ->name('lessons.mark-started')
        ->middleware('can:view_assigned_lessons');

    Route::prefix('student')->as('student.')->middleware(['role:student', 'can:view_assigned_lessons'])->group(function () {
        Route::resource('lesson-requests', \App\Http\Controllers\Student\LessonRequestController::class)
            ->only(['index', 'create', 'store']);

        Route::patch('lesson-requests/{lessonRequest}/accept-suggestion', [\App\Http\Controllers\Student\LessonRequestController::class, 'acceptSuggestion'])
            ->name('lesson-requests.accept-suggestion');

        Route::get('calendar/events', [\App\Http\Controllers\Calendar\LessonCalendarController::class, 'studentEvents'])
            ->name('calendar.events');
    });

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/open', [NotificationController::class, 'open'])->name('notifications.open');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // Teacher/admin assignments dashboard
    Route::get('/admin/assignments', \App\Livewire\Backend\Lessons\AssignmentDashboard::class)
        ->name('backend.assignments.index')
        ->middleware(['can:view_backend', 'can:assign_lessons']);
    
    // Minimal profile routes used by the navigation
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});
/**
 * File Manager Routes.
 */
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth', 'can:view_backend']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
