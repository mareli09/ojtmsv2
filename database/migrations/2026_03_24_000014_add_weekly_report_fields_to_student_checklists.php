<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('student_checklists', function (Blueprint $table) {
            // Weekly Report - Student submission fields
            $table->string('student_weekly_week')->nullable()->after('faculty_dtr_reviewed_at');
            $table->text('student_weekly_task_description')->nullable()->after('student_weekly_week');
            $table->text('student_weekly_supervisor_feedback')->nullable()->after('student_weekly_task_description');
            $table->json('student_weekly_files')->nullable()->after('student_weekly_supervisor_feedback');
            $table->timestamp('student_weekly_submitted_at')->nullable()->after('student_weekly_files');
            
            // Faculty review fields for Weekly Report
            $table->text('faculty_weekly_remarks')->nullable()->after('student_weekly_submitted_at');
            $table->timestamp('faculty_weekly_reviewed_at')->nullable()->after('faculty_weekly_remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_checklists', function (Blueprint $table) {
            $table->dropColumn([
                'student_weekly_week',
                'student_weekly_task_description',
                'student_weekly_supervisor_feedback',
                'student_weekly_files',
                'student_weekly_submitted_at',
                'faculty_weekly_remarks',
                'faculty_weekly_reviewed_at',
            ]);
        });
    }
};
