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
        Schema::table('Submissions', function (Blueprint $table) {
            $table->foreign(['DefenseID'], 'fk_submissions_defense')->references(['DefenseID'])->on('Defenses')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['ProposalID'], 'fk_submissions_proposal')->references(['ProposalID'])->on('Proposals')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['UploadedByUserID'], 'fk_submissions_user')->references(['UserID'])->on('Users')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Submissions', function (Blueprint $table) {
            $table->dropForeign('fk_submissions_defense');
            $table->dropForeign('fk_submissions_proposal');
            $table->dropForeign('fk_submissions_user');
        });
    }
};
