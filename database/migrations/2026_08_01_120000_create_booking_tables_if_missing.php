<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('instruments')) {
            Schema::create('instruments', function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index('is_active');
            });
        }

        if (! Schema::hasTable('instrument_teacher')) {
            Schema::create('instrument_teacher', function (Blueprint $table): void {
                $table->foreignId('teacher_id')
                    ->constrained('users')
                    ->cascadeOnDelete();
                $table->foreignId('instrument_id')
                    ->constrained('instruments')
                    ->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['teacher_id', 'instrument_id']);
                $table->index('teacher_id');
                $table->index('instrument_id');
            });
        }

        if (! Schema::hasTable('teacher_availability')) {
            Schema::create('teacher_availability', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('teacher_id')
                    ->constrained('users')
                    ->cascadeOnDelete();
                $table->unsignedTinyInteger('day_of_week');
                $table->time('start_time');
                $table->time('end_time');
                $table->boolean('is_available')->default(true);
                $table->timestamps();

                $table->index('teacher_id');
                $table->index(['teacher_id', 'day_of_week']);
                $table->index('day_of_week');
                $table->index('is_available');
            });
        }

        if (! Schema::hasTable('lesson_requests')) {
            Schema::create('lesson_requests', function (Blueprint $table): void {
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
                $table->integer('lesson_duration')->comment('Lesson duration stored in minutes');
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

        if (! Schema::hasTable('booked_lessons')) {
            Schema::create('booked_lessons', function (Blueprint $table): void {
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
                $table->integer('lesson_duration')
                    ->default(60)
                    ->comment('Lesson duration stored in minutes');
                $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
                $table->dateTime('completed_at')->nullable();
                $table->dateTime('cancelled_at')->nullable();
                $table->dateTime('rescheduled_at')->nullable();
                $table->text('cancellation_reason')->nullable();
                $table->timestamps();

                $table->index('student_id');
                $table->index('teacher_id');
                $table->index('instrument_id');
                $table->index('status');
                $table->index(['teacher_id', 'lesson_date']);
            });
        }

        if (Schema::hasTable('lessons') && ! Schema::hasColumn('lessons', 'lesson_duration')) {
            Schema::table('lessons', function (Blueprint $table): void {
                $table->integer('lesson_duration')
                    ->default(60)
                    ->comment('Lesson duration stored in minutes')
                    ->after('instrument');
            });
        }

        if (Schema::hasTable('lesson_requests') && ! Schema::hasColumn('lesson_requests', 'lesson_duration')) {
            Schema::table('lesson_requests', function (Blueprint $table): void {
                $table->integer('lesson_duration')
                    ->default(60)
                    ->comment('Lesson duration stored in minutes')
                    ->after('requested_end_time');
            });
        }

        if (Schema::hasTable('booked_lessons') && ! Schema::hasColumn('booked_lessons', 'lesson_duration')) {
            Schema::table('booked_lessons', function (Blueprint $table): void {
                $table->integer('lesson_duration')
                    ->default(60)
                    ->comment('Lesson duration stored in minutes')
                    ->after('lesson_end_time');
            });
        }

        if (Schema::hasTable('booked_lessons') && ! Schema::hasColumn('booked_lessons', 'completed_at')) {
            Schema::table('booked_lessons', function (Blueprint $table): void {
                $table->dateTime('completed_at')->nullable()->after('status');
                $table->dateTime('cancelled_at')->nullable()->after('completed_at');
                $table->dateTime('rescheduled_at')->nullable()->after('cancelled_at');
                $table->text('cancellation_reason')->nullable()->after('rescheduled_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('booked_lessons') && Schema::hasColumn('booked_lessons', 'lesson_duration')) {
            Schema::table('booked_lessons', function (Blueprint $table): void {
                $table->dropColumn('lesson_duration');
            });
        }

        if (Schema::hasTable('booked_lessons') && Schema::hasColumn('booked_lessons', 'completed_at')) {
            Schema::table('booked_lessons', function (Blueprint $table): void {
                $table->dropColumn([
                    'completed_at',
                    'cancelled_at',
                    'rescheduled_at',
                    'cancellation_reason',
                ]);
            });
        }

        if (Schema::hasTable('lesson_requests') && Schema::hasColumn('lesson_requests', 'lesson_duration')) {
            Schema::table('lesson_requests', function (Blueprint $table): void {
                $table->dropColumn('lesson_duration');
            });
        }

        if (Schema::hasTable('lessons') && Schema::hasColumn('lessons', 'lesson_duration')) {
            Schema::table('lessons', function (Blueprint $table): void {
                $table->dropColumn('lesson_duration');
            });
        }

        Schema::dropIfExists('booked_lessons');
        Schema::dropIfExists('lesson_requests');
        Schema::dropIfExists('teacher_availability');
        Schema::dropIfExists('instrument_teacher');
        Schema::dropIfExists('instruments');
    }
};