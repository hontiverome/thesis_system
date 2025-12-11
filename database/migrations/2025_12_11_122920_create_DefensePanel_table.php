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
        Schema::create('DefensePanel', function (Blueprint $table) {
            $table->string('DefenseID', 10);
            $table->integer('PanelistUserID')->index('fk_panel_user');
            $table->string('Status', 20)->nullable();

            $table->primary(['DefenseID', 'PanelistUserID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DefensePanel');
    }
};
