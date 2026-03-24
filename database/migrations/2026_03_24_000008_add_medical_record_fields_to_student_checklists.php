<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('student_checklists', function (Blueprint $table) {
            $table->text('student_files')->nullable()->after('student_file');
            $table->string('student_clinic_name')->nullable()->after('student_files');
            $table->string('student_clinic_address')->nullable()->after('student_clinic_name');
            $table->enum('student_submission_status', ['pending', 'submitted', 'needs_revision'])->default('pending')->after('student_submitted_at');
            $table->datetime('student_enrolled_at')->nullable()->after('student_submission_status');
        });
    }

    public function down()
    {
        Schema::table('student_checklists', function (Blueprint $table) {
            $table->dropColumn(['student_files', 'student_clinic_name', 'student_clinic_address', 'student_submission_status', 'student_enrolled_at']);
        });
    }
};