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
        Schema::table('Groups', function (Blueprint $table) {
            $table->foreign(['AdviserUserID'], 'fk_groups_adviser')->references(['UserID'])->on('Users')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Groups', function (Blueprint $table) {
            $table->dropForeign('fk_groups_adviser');
        });
    }
};
