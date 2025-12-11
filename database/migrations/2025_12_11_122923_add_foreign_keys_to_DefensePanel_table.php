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
        Schema::table('DefensePanel', function (Blueprint $table) {
            $table->foreign(['DefenseID'], 'fk_panel_defense')->references(['DefenseID'])->on('Defenses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['PanelistUserID'], 'fk_panel_user')->references(['UserID'])->on('Users')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DefensePanel', function (Blueprint $table) {
            $table->dropForeign('fk_panel_defense');
            $table->dropForeign('fk_panel_user');
        });
    }
};
