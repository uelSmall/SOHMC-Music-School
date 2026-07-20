<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_assignment_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_student_assignment_id')
                ->constrained('lesson_student_assignments')
                ->cascadeOnDelete();
            $table->foreignId('teacher_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();

            $table->index(['lesson_student_assignment_id', 'created_at']);
            $table->index('teacher_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_assignment_comments');
    }
};
