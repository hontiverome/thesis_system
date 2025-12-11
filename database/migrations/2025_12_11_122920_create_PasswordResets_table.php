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
        Schema::create('PasswordResets', function (Blueprint $table) {
            $table->integer('ResetID', true);
            $table->integer('UserID')->index('fk_resets_user');
            $table->string('ResetToken');
            $table->dateTime('CreatedAt');
            $table->dateTime('ExpiresAt');
            $table->boolean('IsUsed')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PasswordResets');
    }
};
