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
        Schema::create('Submissions', function (Blueprint $table) {
            $table->string('FileID', 10)->primary();
            $table->string('ProposalID', 10)->nullable()->index('fk_submissions_proposal');
            $table->string('DefenseID', 10)->nullable()->index('fk_submissions_defense');
            $table->string('FileType', 50)->nullable();
            $table->text('FilePath');
            $table->integer('UploadedByUserID')->index('fk_submissions_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Submissions');
    }
};
