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
        Schema::create('Courses', function (Blueprint $table) {
            $table->string('CourseID', 10)->primary();
            $table->string('CourseName', 50);
            $table->string('PrerequisiteCourseID', 10)->nullable()->index('fk_courses_prereq');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Courses');
    }
};
