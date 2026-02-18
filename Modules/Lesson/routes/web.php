<?php

use Illuminate\Support\Facades\Route;
use Modules\Lesson\Http\Controllers\LessonController;

Route::prefix('admin')->as('backend.')->middleware(['auth', 'can:view_backend'])->group(function () {
    Route::resource('lessons', LessonController::class);
});
