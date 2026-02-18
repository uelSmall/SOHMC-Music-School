<?php

namespace Modules\Lesson\Providers;

use Illuminate\Support\ServiceProvider;

class LessonServiceProvider extends ServiceProvider
{
    protected $moduleName = 'Lesson';
    protected $moduleNameLower = 'lesson';

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(base_path('Modules/Lesson/Database/migrations'));
    }
}

