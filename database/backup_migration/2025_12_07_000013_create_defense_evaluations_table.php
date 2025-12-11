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
        Schema::create('defense_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('defense_id')->constrained()->onDelete('cascade');
            $table->foreignId('panelist_user_id')->constrained('users')->onDelete('cascade');
            $table->string('verdict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defense_evaluations');
    }
};
