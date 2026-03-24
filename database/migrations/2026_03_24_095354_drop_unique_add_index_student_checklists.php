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
        // Step 1: add standalone index on section_id so the FK constraint
        // no longer depends on the composite unique index
        Schema::table('student_checklists', function (Blueprint $table) {
            $table->index('section_id', 'sc_section_id_idx');
        });

        // Step 2: now we can safely drop the unique composite index
        Schema::table('student_checklists', function (Blueprint $table) {
            $table->dropUnique('student_checklists_section_id_student_id_item_unique');
        });

        // Step 3: add a non-unique composite index for query performance
        Schema::table('student_checklists', function (Blueprint $table) {
            $table->index(['section_id', 'student_id', 'item'], 'sc_section_student_item_idx');
        });
    }

    public function down(): void
    {
        Schema::table('student_checklists', function (Blueprint $table) {
            $table->dropIndex('sc_section_student_item_idx');
            $table->unique(['section_id', 'student_id', 'item']);
            $table->dropIndex('sc_section_id_idx');
        });
    }
};
