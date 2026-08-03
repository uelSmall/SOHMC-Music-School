<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instrument_teacher', function (Blueprint $table) {
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

    public function down(): void
    {
        Schema::dropIfExists('instrument_teacher');
    }
};