<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->foreignId('teacher_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->after('id');
            
            $table->string('file_path')->nullable()->after('content');
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeignIdFor('teacher_id');
            $table->dropColumn('file_path');
        });
    }
};
