<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_student_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')
                ->constrained('lessons')
                ->cascadeOnDelete();
            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->timestamp('assigned_at')->useCurrent();
            $table->date('due_date')->nullable();
            $table->string('status')->default('assigned')->comment('assigned, started, in_progress, completed');
            $table->timestamps();

            $table->unique(['lesson_id', 'student_id']);
            $table->index('student_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_student_assignments');
    }
};
