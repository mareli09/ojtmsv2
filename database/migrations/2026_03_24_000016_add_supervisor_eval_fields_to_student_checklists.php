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
            $table->string('student_supervisor_eval_file')->nullable()->after('faculty_appraisal_reviewed_at');
            $table->string('student_supervisor_eval_grade')->nullable()->after('student_supervisor_eval_file');
            $table->datetime('student_supervisor_eval_submitted_at')->nullable()->after('student_supervisor_eval_grade');
            $table->text('faculty_supervisor_eval_remarks')->nullable()->after('student_supervisor_eval_submitted_at');
            $table->datetime('faculty_supervisor_eval_reviewed_at')->nullable()->after('faculty_supervisor_eval_remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_checklists', function (Blueprint $table) {
            $table->dropColumn(['student_supervisor_eval_file', 'student_supervisor_eval_grade', 'student_supervisor_eval_submitted_at', 'faculty_supervisor_eval_remarks', 'faculty_supervisor_eval_reviewed_at']);
        });
    }
};
