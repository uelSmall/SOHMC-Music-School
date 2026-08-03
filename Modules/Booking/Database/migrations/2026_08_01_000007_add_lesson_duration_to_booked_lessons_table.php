<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booked_lessons', function (Blueprint $table): void {
            $table->integer('lesson_duration')
                ->default(60)
                ->comment('Lesson duration stored in minutes')
                ->after('lesson_end_time');
        });
    }

    public function down(): void
    {
        Schema::table('booked_lessons', function (Blueprint $table): void {
            $table->dropColumn('lesson_duration');
        });
    }
};