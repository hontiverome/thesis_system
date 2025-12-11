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
        Schema::table('ResearchArchive', function (Blueprint $table) {
            $table->foreign(['ProposalID'], 'fk_archive_proposal')->references(['ProposalID'])->on('Proposals')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ResearchArchive', function (Blueprint $table) {
            $table->dropForeign('fk_archive_proposal');
        });
    }
};
