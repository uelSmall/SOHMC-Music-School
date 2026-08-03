<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lesson_requests', function (Blueprint $table): void {
            $table->integer('lesson_duration')
                ->comment('Lesson duration stored in minutes')
                ->after('requested_end_time');
        });

        Schema::table('lessons', function (Blueprint $table): void {
            $table->integer('lesson_duration')
                ->comment('Lesson duration stored in minutes')
                ->after('instrument');
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table): void {
            $table->dropColumn('lesson_duration');
        });

        Schema::table('lesson_requests', function (Blueprint $table): void {
            $table->dropColumn('lesson_duration');
        });
    }
};