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
        Schema::table('PasswordResets', function (Blueprint $table) {
            $table->foreign(['UserID'], 'fk_resets_user')->references(['UserID'])->on('Users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('PasswordResets', function (Blueprint $table) {
            $table->dropForeign('fk_resets_user');
        });
    }
};
