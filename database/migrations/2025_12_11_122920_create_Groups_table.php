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
        Schema::create('Groups', function (Blueprint $table) {
            $table->string('GroupID', 10)->primary();
            $table->string('GroupCode', 20)->nullable()->unique('groupcode');
            $table->integer('YearLevel')->nullable();
            $table->integer('AdviserUserID')->index('fk_groups_adviser');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Groups');
    }
};
