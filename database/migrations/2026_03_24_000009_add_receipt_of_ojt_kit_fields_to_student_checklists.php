<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('student_checklists', function (Blueprint $table) {
            $table->datetime('student_paid_date')->nullable()->after('student_enrolled_at');
            $table->string('student_receipt_number')->nullable()->after('student_paid_date');
        });
    }

    public function down()
    {
        Schema::table('student_checklists', function (Blueprint $table) {
            $table->dropColumn(['student_paid_date', 'student_receipt_number']);
        });
    }
};