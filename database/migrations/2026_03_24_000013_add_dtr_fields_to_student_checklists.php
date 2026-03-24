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
            // DTR (Daily Time Record) - Student submission fields
            $table->string('student_dtr_week')->nullable()->after('student_supervisor_signed_by');
            $table->decimal('student_dtr_hours', 8, 2)->nullable()->after('student_dtr_week');
            $table->string('student_dtr_validated_by')->nullable()->after('student_dtr_hours');
            $table->integer('student_dtr_total_hours')->default(0)->after('student_dtr_validated_by');
            
            // Faculty review fields for DTR
            $table->decimal('faculty_dtr_target_hours', 8, 2)->default(720)->after('student_dtr_total_hours');
            $table->timestamp('faculty_dtr_reviewed_at')->nullable()->after('faculty_dtr_target_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_checklists', function (Blueprint $table) {
            $table->dropColumn([
                'student_dtr_week',
                'student_dtr_hours',
                'student_dtr_validated_by',
                'student_dtr_total_hours',
                'faculty_dtr_target_hours',
                'faculty_dtr_reviewed_at',
            ]);
        });
    }
};
