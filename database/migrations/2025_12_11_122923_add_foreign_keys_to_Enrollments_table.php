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
        Schema::table('Enrollments', function (Blueprint $table) {
            $table->foreign(['CourseID'], 'fk_enrollments_course')->references(['CourseID'])->on('Courses')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['GroupID'], 'fk_enrollments_group')->references(['GroupID'])->on('Groups')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Enrollments', function (Blueprint $table) {
            $table->dropForeign('fk_enrollments_course');
            $table->dropForeign('fk_enrollments_group');
        });
    }
};
