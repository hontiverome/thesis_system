<?php

use Illuminate.Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate.Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id('enrollment_id');
            $table->foreignId('group_id')->constrained('groups', 'group_id')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses', 'course_id')->cascadeOnDelete();
            $table->string('school_year');
            $table->string('semester');
            $table->timestamps();
        });
    }
};