<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('student_checklists', function (Blueprint $table) {
            $table->string('student_guardian_name')->nullable()->after('student_receipt_number');
            $table->string('student_guardian_contact')->nullable()->after('student_guardian_name');
            $table->string('student_guardian_email')->nullable()->after('student_guardian_contact');
            $table->string('student_guardian_social')->nullable()->after('student_guardian_email');
        });
    }

    public function down()
    {
        Schema::table('student_checklists', function (Blueprint $table) {
            $table->dropColumn([
                'student_guardian_name',
                'student_guardian_contact',
                'student_guardian_email',
                'student_guardian_social',
            ]);
        });
    }
};