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
        Schema::create('Proposals', function (Blueprint $table) {
            $table->string('ProposalID', 10)->primary();
            $table->string('EnrollmentID', 10)->index('fk_proposals_enrollment');
            $table->string('ResearchTitle', 500)->nullable()->unique('researchtitle');
            $table->date('SubmissionDate')->nullable();
            $table->date('Deadline')->nullable();
            $table->string('Status', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Proposals');
    }
};
