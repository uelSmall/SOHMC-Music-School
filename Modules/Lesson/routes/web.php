<?php

use Illuminate\Support\Facades\Route;
use Modules\Lesson\Http\Controllers\LessonController;

Route::prefix('teacher')->as('teacher.')->middleware(['auth', 'role:teacher|administrator|super admin'])->group(function () {
    Route::resource('lessons', LessonController::class);
});

Route::prefix('admin')->as('backend.')->middleware(['auth', 'can:view_backend', 'can:manage_lessons'])->group(function () {
    Route::resource('lessons', LessonController::class);
});
