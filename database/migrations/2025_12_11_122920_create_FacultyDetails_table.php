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
        Schema::create('FacultyDetails', function (Blueprint $table) {
            $table->string('FacultyDetailID', 10)->primary();
            $table->integer('UserID')->unique('userid');
            $table->string('FacultyType', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('FacultyDetails');
    }
};
