<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('booked_lessons') && Schema::hasColumn('booked_lessons', 'completed_at')) {
            return;
        }

        Schema::table('booked_lessons', function (Blueprint $table): void {
            $table->dateTime('completed_at')->nullable()->after('status');
            $table->dateTime('cancelled_at')->nullable()->after('completed_at');
            $table->dateTime('rescheduled_at')->nullable()->after('cancelled_at');
            $table->text('cancellation_reason')->nullable()->after('rescheduled_at');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('booked_lessons') || ! Schema::hasColumn('booked_lessons', 'completed_at')) {
            return;
        }

        Schema::table('booked_lessons', function (Blueprint $table): void {
            $table->dropColumn([
                'completed_at',
                'cancelled_at',
                'rescheduled_at',
                'cancellation_reason',
            ]);
        });
    }
};