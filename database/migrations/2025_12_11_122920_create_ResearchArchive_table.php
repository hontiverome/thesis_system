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
        Schema::create('ResearchArchive', function (Blueprint $table) {
            $table->string('ArchiveID', 10)->primary();
            $table->string('ProposalID', 10)->unique('proposalid');
            $table->text('AbstractFilePath')->nullable();
            $table->text('FullManuscriptPath')->nullable();
            $table->date('PublishedDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ResearchArchive');
    }
};
