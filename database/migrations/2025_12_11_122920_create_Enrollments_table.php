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
        Schema::create('Enrollments', function (Blueprint $table) {
            $table->string('EnrollmentID', 10)->primary();
            $table->string('GroupID', 10)->index('fk_enrollments_group');
            $table->string('CourseID', 10)->index('fk_enrollments_course');
            $table->string('SchoolYear', 10)->nullable();
            $table->string('Semester', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Enrollments');
    }
};
