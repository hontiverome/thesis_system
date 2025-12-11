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
        Schema::create('UserRoles', function (Blueprint $table) {
            $table->integer('UserID');
            $table->integer('RoleID')->index('fk_userroles_role');

            $table->primary(['UserID', 'RoleID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('UserRoles');
    }
};
