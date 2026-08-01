<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booked_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_request_id')
                ->unique()
                ->constrained('lesson_requests')
                ->cascadeOnDelete();
            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('teacher_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('instrument_id')
                ->constrained('instruments')
                ->cascadeOnDelete();
            $table->date('lesson_date');
            $table->time('lesson_start_time');
            $table->time('lesson_end_time');
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();

            $table->index('student_id');
            $table->index('teacher_id');
            $table->index('instrument_id');
            $table->index('status');
            $table->index(['teacher_id', 'lesson_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booked_lessons');
    }
};