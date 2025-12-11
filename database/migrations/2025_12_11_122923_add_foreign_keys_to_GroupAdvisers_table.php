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
        Schema::table('GroupAdvisers', function (Blueprint $table) {
            $table->foreign(['GroupID'], 'fk_advisers_group')->references(['GroupID'])->on('Groups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['AdviserUserID'], 'fk_advisers_user')->references(['UserID'])->on('Users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('GroupAdvisers', function (Blueprint $table) {
            $table->dropForeign('fk_advisers_group');
            $table->dropForeign('fk_advisers_user');
        });
    }
};
