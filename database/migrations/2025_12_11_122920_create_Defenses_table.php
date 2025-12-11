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
        Schema::create('Defenses', function (Blueprint $table) {
            $table->string('DefenseID', 10)->primary();
            $table->string('EnrollmentID', 10)->index('fk_defenses_enrollment');
            $table->string('ProposalID', 10)->index('fk_defenses_proposal');
            $table->string('DefenseType', 50)->nullable();
            $table->dateTime('Schedule')->nullable();
            $table->string('OverallVerdict', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Defenses');
    }
};
