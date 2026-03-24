<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('student_checklists', function (Blueprint $table) {
            $table->datetime('student_endorsement_date')->nullable()->after('student_guardian_social');
            $table->datetime('student_start_date')->nullable()->after('student_endorsement_date');
            $table->string('student_supervisor_signed_by')->nullable()->after('student_start_date');
        });
    }

    public function down()
    {
        Schema::table('student_checklists', function (Blueprint $table) {
            $table->dropColumn(['student_endorsement_date', 'student_start_date', 'student_supervisor_signed_by']);
        });
    }
};