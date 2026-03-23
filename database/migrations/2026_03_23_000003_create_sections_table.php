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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., A1, A2, B1
            $table->string('school_year'); // e.g., 2024-2025
            $table->string('term'); // e.g., First Term, Summer
            $table->string('day'); // e.g., Monday, Tuesday
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('days_count')->nullable(); // number of days/weeks
            $table->string('room'); // e.g., Room 101
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive', 'completed'])->default('active');
            $table->unsignedBigInteger('faculty_id')->nullable();
            $table->integer('capacity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
