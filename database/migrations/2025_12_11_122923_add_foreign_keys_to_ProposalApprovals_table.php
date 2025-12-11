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
        Schema::table('ProposalApprovals', function (Blueprint $table) {
            $table->foreign(['ProposalID'], 'fk_approvals_proposal')->references(['ProposalID'])->on('Proposals')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['ApprovedUserID'], 'fk_approvals_user')->references(['UserID'])->on('Users')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ProposalApprovals', function (Blueprint $table) {
            $table->dropForeign('fk_approvals_proposal');
            $table->dropForeign('fk_approvals_user');
        });
    }
};
