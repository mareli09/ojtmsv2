<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('item');
            $table->string('student_file')->nullable();
            $table->datetime('student_encoded_at')->nullable();
            $table->datetime('student_submitted_at')->nullable();
            $table->text('student_remarks')->nullable();
            $table->enum('faculty_status', ['pending', 'approved', 'declined'])->default('pending');
            $table->text('faculty_remarks')->nullable();
            $table->datetime('faculty_reviewed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['section_id', 'student_id', 'item']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_checklists');
    }
};