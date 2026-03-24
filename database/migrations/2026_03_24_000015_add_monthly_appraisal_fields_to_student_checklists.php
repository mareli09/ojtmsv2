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
            // Monthly Appraisal - Student submission fields
            $table->string('student_appraisal_month')->nullable()->after('faculty_weekly_reviewed_at');
            $table->string('student_appraisal_file')->nullable()->after('student_appraisal_month');
            $table->text('student_appraisal_feedback')->nullable()->after('student_appraisal_file');
            $table->string('student_appraisal_grade_rating')->nullable()->after('student_appraisal_feedback');
            $table->string('student_appraisal_evaluated_by')->nullable()->after('student_appraisal_grade_rating');
            $table->timestamp('student_appraisal_submitted_at')->nullable()->after('student_appraisal_evaluated_by');
            
            // Faculty review fields for Monthly Appraisal
            $table->text('faculty_appraisal_remarks')->nullable()->after('student_appraisal_submitted_at');
            $table->timestamp('faculty_appraisal_reviewed_at')->nullable()->after('faculty_appraisal_remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_checklists', function (Blueprint $table) {
            $table->dropColumn([
                'student_appraisal_month',
                'student_appraisal_file',
                'student_appraisal_feedback',
                'student_appraisal_grade_rating',
                'student_appraisal_evaluated_by',
                'student_appraisal_submitted_at',
                'faculty_appraisal_remarks',
                'faculty_appraisal_reviewed_at',
            ]);
        });
    }
};
