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
        Schema::table('Defenses', function (Blueprint $table) {
            $table->foreign(['EnrollmentID'], 'fk_defenses_enrollment')->references(['EnrollmentID'])->on('Enrollments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['ProposalID'], 'fk_defenses_proposal')->references(['ProposalID'])->on('Proposals')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Defenses', function (Blueprint $table) {
            $table->dropForeign('fk_defenses_enrollment');
            $table->dropForeign('fk_defenses_proposal');
        });
    }
};
