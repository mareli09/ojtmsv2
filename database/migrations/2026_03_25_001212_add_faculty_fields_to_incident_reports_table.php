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
        Schema::table('incident_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('section_id')->nullable()->after('student_id');
            $table->string('attachment')->nullable()->after('action_taken');
            $table->string('faculty_status')->default('pending')->after('attachment');
            $table->text('faculty_remarks')->nullable()->after('faculty_status');
            $table->timestamp('faculty_reviewed_at')->nullable()->after('faculty_remarks');
        });
    }

    public function down(): void
    {
        Schema::table('incident_reports', function (Blueprint $table) {
            $table->dropColumn(['section_id', 'attachment', 'faculty_status', 'faculty_remarks', 'faculty_reviewed_at']);
        });
    }
};
