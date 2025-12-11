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
        Schema::table('Proposals', function (Blueprint $table) {
            $table->foreign(['EnrollmentID'], 'fk_proposals_enrollment')->references(['EnrollmentID'])->on('Enrollments')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Proposals', function (Blueprint $table) {
            $table->dropForeign('fk_proposals_enrollment');
        });
    }
};
