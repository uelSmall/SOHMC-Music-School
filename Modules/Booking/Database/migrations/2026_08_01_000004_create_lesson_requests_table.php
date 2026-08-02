<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('teacher_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('instrument_id')
                ->constrained('instruments')
                ->cascadeOnDelete();
            $table->date('requested_date');
            $table->time('requested_start_time');
            $table->time('requested_end_time');
            $table->date('suggested_date')->nullable();
            $table->time('suggested_start_time')->nullable();
            $table->time('suggested_end_time')->nullable();
            $table->enum('status', [
                'pending',
                'teacher_confirmed',
                'teacher_rescheduled',
                'student_accepted',
                'student_declined',
                'cancelled',
            ])->default('pending');
            $table->text('student_note')->nullable();
            $table->text('teacher_note')->nullable();
            $table->timestamps();

            $table->index('student_id');
            $table->index('teacher_id');
            $table->index('instrument_id');
            $table->index('status');
            $table->index(['teacher_id', 'requested_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_requests');
    }
};