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
            $table->string('student_coc_file')->nullable()->after('faculty_supervisor_eval_reviewed_at');
            $table->string('student_coc_signed_by')->nullable()->after('student_coc_file');
            $table->string('student_coc_company')->nullable()->after('student_coc_signed_by');
            $table->date('student_coc_receive_date')->nullable()->after('student_coc_company');
            $table->date('student_coc_date_issued')->nullable()->after('student_coc_receive_date');
            $table->datetime('student_coc_submitted_at')->nullable()->after('student_coc_date_issued');
            $table->text('faculty_coc_remarks')->nullable()->after('student_coc_submitted_at');
            $table->datetime('faculty_coc_reviewed_at')->nullable()->after('faculty_coc_remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_checklists', function (Blueprint $table) {
            $table->dropColumn(['student_coc_file', 'student_coc_signed_by', 'student_coc_company', 'student_coc_receive_date', 'student_coc_date_issued', 'student_coc_submitted_at', 'faculty_coc_remarks', 'faculty_coc_reviewed_at']);
        });
    }
};
