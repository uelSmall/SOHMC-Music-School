<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lesson_assignment_comments', function (Blueprint $table) {
            $table->renameColumn('teacher_id', 'user_id');
            $table->timestamp('read_at')->nullable()->after('body');
        });
    }

    public function down(): void
    {
        Schema::table('lesson_assignment_comments', function (Blueprint $table) {
            $table->dropColumn('read_at');
            $table->renameColumn('user_id', 'teacher_id');
        });
    }
};
