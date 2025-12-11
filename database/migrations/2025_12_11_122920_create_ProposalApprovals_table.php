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
        Schema::create('ProposalApprovals', function (Blueprint $table) {
            $table->string('ApprovalID', 10)->primary();
            $table->string('ProposalID', 10)->index('fk_approvals_proposal');
            $table->integer('ApprovedUserID')->index('fk_approvals_user');
            $table->string('ApprovalRole', 50)->nullable();
            $table->string('Status', 20)->nullable();
            $table->text('Remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ProposalApprovals');
    }
};
