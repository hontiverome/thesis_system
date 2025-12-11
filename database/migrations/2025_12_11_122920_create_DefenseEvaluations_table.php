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
        Schema::create('DefenseEvaluations', function (Blueprint $table) {
            $table->string('EvaluationID', 10)->primary();
            $table->string('DefenseID', 10)->index('fk_eval_defense');
            $table->integer('PanelistUserID')->index('fk_eval_user');
            $table->string('Verdict', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DefenseEvaluations');
    }
};
