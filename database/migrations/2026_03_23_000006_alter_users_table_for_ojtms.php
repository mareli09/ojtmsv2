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
        Schema::table('users', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('users', 'employee_id')) {
                $table->string('employee_id')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('users', 'student_id')) {
                $table->string('student_id')->nullable()->unique()->after('employee_id');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'faculty', 'student'])->default('student')->after('student_id');
            }
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->after('role');
            }
            if (!Schema::hasColumn('users', 'middle_name')) {
                $table->string('middle_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->after('middle_name');
            }
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->after('last_name');
            }
            if (!Schema::hasColumn('users', 'contact')) {
                $table->string('contact')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department')->nullable()->after('contact');
            }
            if (!Schema::hasColumn('users', 'section_id')) {
                $table->unsignedBigInteger('section_id')->nullable()->after('department');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('section_id');
            }
            if (!Schema::hasColumn('users', 'last_activity_at')) {
                $table->timestamp('last_activity_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes()->after('last_activity_at');
            }
        });

        // Add foreign key if it doesn't exist
        if (!Schema::hasColumn('users', 'section_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('section_id')->references('id')->on('sections')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'employee_id',
                'student_id',
                'role',
                'first_name',
                'middle_name',
                'last_name',
                'username',
                'contact',
                'department',
                'section_id',
                'status',
                'last_activity_at',
                'deleted_at',
            ]);
        });
    }
};
