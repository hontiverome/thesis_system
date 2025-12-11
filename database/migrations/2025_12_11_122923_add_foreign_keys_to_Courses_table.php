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
        Schema::table('Courses', function (Blueprint $table) {
            $table->foreign(['PrerequisiteCourseID'], 'fk_courses_prereq')->references(['CourseID'])->on('Courses')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Courses', function (Blueprint $table) {
            $table->dropForeign('fk_courses_prereq');
        });
    }
};
